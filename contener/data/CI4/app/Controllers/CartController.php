<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Events\EventDispatcher;
use App\Events\AchatTermineEvent;
use App\Events\Observers\InventoryManager;
use App\Events\Observers\NotificationService;

class CartController extends BaseController
{
    private const COOKIE_NAME = 'tempo_cart';
    private const COOKIE_DAYS = 30;

    public function show()
    {
        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();

        // Nettoyage des beats non achetables (sold/inactive)
        $removed = $itemModel->removeSoldItems($cartId);

        $items = $itemModel->getDetailedItems($cartId);

        $totalCents = 0;
        foreach ($items as $it) {
            $totalCents += (int)($it['price_cents'] ?? 0);
        }

        return view('cart/show', [
            'title'   => 'Panier',
            'items'   => $items,
            'total'   => $totalCents / 100,
            'removed' => $removed,
        ]);
    }

    public function add(int $beatId)
    {
        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();

        try {
            $itemModel->addBeat($cartId, $beatId);
            return redirect()->back()->with('success', 'Ajouté au panier.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove(int $beatId)
    {
        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $itemModel->removeBeat($cartId, $beatId);

        return redirect()->to('/cart')->with('success', 'Retiré du panier.');
    }

    public function removeLine(int $beatId)
    {
        return $this->remove($beatId);
    }

    public function checkoutForm()
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        $isLoggedIn = (bool)session()->get('isLoggedIn');

        if (!$isLoggedIn || $userId <= 0) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour finaliser l’achat.');
        }

        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $items = $itemModel->getDetailedItems($cartId);

        if (empty($items)) {
            return redirect()->to('/cart')->with('error', 'Ton panier est vide.');
        }

        $totalCents = 0;
        foreach ($items as $it) {
            $totalCents += (int)($it['price_cents'] ?? 0);
        }

        return view('cart/checkout', [
            'title' => 'Paiement',
            'items' => $items,
            'total' => $totalCents / 100,
        ]);
    }

    public function checkout()
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        $isLoggedIn = (bool)session()->get('isLoggedIn');

        if (!$isLoggedIn || $userId <= 0) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour finaliser l’achat.');
        }

        [$cartId] = $this->getOrCreateCartId();

        $db = db_connect();
        $itemModel = new CartItemModel();
        $rows = $itemModel->getDetailedItems($cartId);

        if (empty($rows)) {
            return redirect()->to('/cart')->with('error', 'Ton panier est vide.');
        }

        $db->transBegin();

        try {
            // recalcul total + vérifs stock
            $totalCents = 0;
            $orderItems = [];

            foreach ($rows as $r) {
                $beatId = (int)$r['beat_id'];

                $fresh = $db->table('beats')->select('id, user_id, title, status, buyer_id, price')
                    ->where('id', $beatId)->get()->getRowArray();

                if (!$fresh || ($fresh['status'] ?? '') !== 'active' || !empty($fresh['buyer_id'])) {
                    throw new \RuntimeException('Un beat n’est plus disponible.');
                }

                $priceCents = (int)round(((float)$fresh['price']) * 100);
                $totalCents += $priceCents;

                $orderItems[] = [
                    'beat_id'     => $beatId,
                    'beat_title'  => (string)$fresh['title'],
                    'price_cents' => $priceCents,
                ];
            }

            // Create order
            $db->table('orders')->insert([
                'user_id'     => $userId,
                'total_cents' => $totalCents,
                'status'      => 'paid',
                'created_at'  => date('Y-m-d H:i:s'),
                'paid_at'     => date('Y-m-d H:i:s'),
            ]);
            $orderId = (int)$db->insertID();

            // Insert order_items + mark beats sold (1 vente unique)
            foreach ($orderItems as $oi) {
                $db->table('order_items')->insert([
                    'order_id'    => $orderId,
                    'beat_id'     => (int)$oi['beat_id'],
                    'beat_title'  => (string)$oi['beat_title'],
                    'price_cents' => (int)$oi['price_cents'],
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);
            }

            // Observer pattern : achat terminé
            $event = new AchatTermineEvent($orderId, $userId, $orderItems);

            // Dispatcher + observers
            $dispatcher = new EventDispatcher();
            $dispatcher->attach(new InventoryManager());
            $dispatcher->attach(new NotificationService());
            $dispatcher->dispatch($event);

            // clear cart
            $itemModel->clearCart($cartId);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaction DB invalide.');
            }

            $db->transCommit();

            return redirect()->to('/checkout/thanks/' . $orderId)->with('success', 'Achat effectué !');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/cart')->with('error', 'Erreur achat : ' . $e->getMessage());
        }
    }

    /**
     * Page "Merci pour votre paiement"
     * GET /checkout/thanks/{orderId}
     */
    public function thanks(int $orderId)
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        $isLoggedIn = (bool)session()->get('isLoggedIn');

        if (!$isLoggedIn || $userId <= 0) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour accéder à cette page.');
        }

        $db = db_connect();

        // Ownership + statut payé
        $order = $db->table('orders')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('/cart')->with('error', 'Commande introuvable ou accès refusé.');
        }

        // Résumé des items achetés
        $items = $db->table('order_items oi')
            ->select('oi.beat_id, oi.beat_title, oi.price_cents')
            ->where('oi.order_id', $orderId)
            ->orderBy('oi.id', 'ASC')
            ->get()
            ->getResultArray();

        $totalCents = (int)($order['total_cents'] ?? 0);

        return view('cart/thanks', [
            'title'      => 'Merci pour votre paiement',
            'order'      => $order,
            'items'      => $items,
            'totalCents' => $totalCents,
        ]);
    }

    /**
     * Retourne [cartId, isLoggedIn]
     */
    private function getOrCreateCartId(): array
    {
        $db = db_connect();
        $isLoggedIn = (bool)session()->get('isLoggedIn');
        $userId = (int)(session()->get('user_id') ?? 0);

        if ($isLoggedIn && $userId > 0) {
            // user cart
            $userCart = $db->table('carts')->where('user_id', $userId)->get()->getRowArray();

            if (!$userCart) {
                $db->table('carts')->insert([
                    'user_id'    => $userId,
                    'guest_token'=> null,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $userCart = $db->table('carts')->where('user_id', $userId)->get()->getRowArray();
            }

            // if guest token exists, merge once
            $guestToken = (string)($this->request->getCookie(self::COOKIE_NAME) ?? '');
            if ($guestToken !== '') {
                $guestCart = $db->table('carts')->where('guest_token', $guestToken)->get()->getRowArray();
                if ($guestCart && (int)$guestCart['id'] !== (int)$userCart['id']) {
                    $db->table('cart_items')
                        ->where('cart_id', (int)$guestCart['id'])
                        ->update(['cart_id' => (int)$userCart['id']]);

                    // delete guest cart + items
                    $itemModel = new CartItemModel();
                    $itemModel->clearCart((int)$guestCart['id']);
                    $db->table('carts')->where('id', (int)$guestCart['id'])->delete();
                }

                // remove cookie
                $this->response->setCookie(self::COOKIE_NAME, '', 1);
            }

            return [(int)$userCart['id'], true];
        }

        // Guest flow
        $guestToken = (string)($this->request->getCookie(self::COOKIE_NAME) ?? '');
        if ($guestToken === '') {
            $guestToken = bin2hex(random_bytes(16));
            $this->response->setCookie([
                'name'     => self::COOKIE_NAME,
                'value'    => $guestToken,
                'expire'   => time() + (self::COOKIE_DAYS * 86400),
                'path'     => '/',
                'secure'   => false,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }

        $guestCart = $db->table('carts')->where('guest_token', $guestToken)->get()->getRowArray();
        if (!$guestCart) {
            $db->table('carts')->insert([
                'user_id'     => null,
                'guest_token' => $guestToken,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
            $guestCart = $db->table('carts')->where('guest_token', $guestToken)->get()->getRowArray();
        }

        return [(int)$guestCart['id'], false];
    }

    public function clear()
    {
        [$cartId] = $this->getOrCreateCartId();
        $itemModel = new \App\Models\CartItemModel();
        $itemModel->clearCart($cartId);

        return redirect()->to('/cart')->with('success', 'Le panier a été vidé.');
    }
}
