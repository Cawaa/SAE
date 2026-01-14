<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Events\EventDispatcher;
use App\Events\AchatTermineEvent;
use App\Events\Observers\InventoryManager;
use App\Events\Observers\NotificationService;

// Contrôleur pour la gestion du panier.
class CartController extends BaseController
{
    
    private const COOKIE_NAME = 'tempo_cart';
    private const COOKIE_DAYS = 30;

    // Affiche le contenu du panier (accès uniquement connecté).
    public function show()
    {
        if (!(bool)session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour accéder au panier.');
        }

        [$cartId] = $this->getOrCreateCartId();
        $isLoggedIn = true;

        $itemModel = new CartItemModel();

        $itemModel->removeSoldItems($cartId);

        $rows = $itemModel->getDetailedItems($cartId);

        $items = [];
        $total = 0.0;
        $hasUnavailable = false;

        // Calcul du total et vérification des indisponibilités
        foreach ($rows as $r) {
            $qty = (int)$r['quantite'];
            $price = (float)$r['price'];
            $line = $qty * $price;

            $isAvailable = ($r['status'] === 'active' && empty($r['buyer_id']));
            if (!$isAvailable) $hasUnavailable = true;

            // total uniquement sur les beats achetables
            if ($isAvailable) {
                $total += $line;
            }

            $items[] = $r;
        }

        return view('cart/show', [
            'items'          => $items,
            'total'          => $total,
            'hasUnavailable' => $hasUnavailable,
            'isLoggedIn'     => $isLoggedIn,
        ]);
    }

    // Ajoute un beat au panier (accès uniquement connecté).
    public function add(int $beatId)
    {
        if (!(bool)session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour ajouter au panier.');
        }

        [$cartId] = $this->getOrCreateCartId();

        // Vérif disponibilité du beat
        $beat = db_connect()->table('beats')
            ->select('id, status, buyer_id')
            ->where('id', $beatId)
            ->get()->getRowArray();

        if (!$beat) {
            return redirect()->to('/cart')->with('error', 'Beat introuvable.');
        }

        $isAvailable = ($beat['status'] === 'active' && empty($beat['buyer_id']));
        if (!$isAvailable) {
            return redirect()->to('/cart')->with('error', 'Ce beat n’est plus disponible.');
        }

        $itemModel = new CartItemModel();
        $itemModel->upsertIncrement($cartId, $beatId, 1);

        db_connect()->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/cart')->with('success', 'Ajouté au panier.');
    }

    // Retire une unité d'un beat du panier (accès uniquement connecté).
    public function remove(int $beatId)
    {
        if (!(bool)session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour modifier le panier.');
        }

        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $itemModel->upsertIncrement($cartId, $beatId, -1);

        db_connect()->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/cart');
    }

    // Retire complètement un beat du panier (accès uniquement connecté).
    public function removeLine(int $beatId)
    {
        if (!(bool)session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour modifier le panier.');
        }

        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $itemModel->removeLine($cartId, $beatId);

        db_connect()->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

        return redirect()->to('/cart');
    }

    // Affiche le formulaire de checkout.
    public function checkoutForm()
    {
        $isLoggedIn = (bool)session()->get('isLoggedIn');
        if (!$isLoggedIn) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour finaliser l’achat.');
        }

        [$cartId] = $this->getOrCreateCartId();

        $itemModel = new CartItemModel();
        $rows = $itemModel->getDetailedItems($cartId);

        $total = 0.0;
        $hasUnavailable = false;

        // Calcul du total et vérification des indisponibilités
        foreach ($rows as $r) {
            $isAvailable = ($r['status'] === 'active' && empty($r['buyer_id']));
            if (!$isAvailable) $hasUnavailable = true;
            if ($isAvailable) $total += (float)$r['price'] * (int)$r['quantite'];
        }

        return view('cart/checkout', [
            'total'          => $total,
            'hasUnavailable' => $hasUnavailable,
        ]);
    }

    // Traite le traitement du checkout.
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
            return redirect()->to('/cart')->with('error', 'Panier vide.');
        }

        // Vérif dispo
        foreach ($rows as $r) {
            $isAvailable = ($r['status'] === 'active' && empty($r['buyer_id']));
            if (!$isAvailable) {
                return redirect()->to('/cart')->with('error', 'Ton panier contient un beat indisponible. Retire-le avant de payer.');
            }
        }

        // Transaction d'achat
        $db->transBegin();
        try {
            $totalCents = 0;
            $orderItems = [];

            foreach ($rows as $r) {
                $totalCents += (int)round(((float)$r['price']) * 100) * (int)$r['quantite'];
            }

            // Create order
            $db->table('orders')->insert([
                'user_id'     => $userId,
                'guest_email' => null,
                'guest_token' => null,
                'total_cents' => $totalCents,
                'status'      => 'paid',
                'created_at'  => date('Y-m-d H:i:s'),
                'paid_at'     => date('Y-m-d H:i:s'),
            ]);
            $orderId = (int)$db->insertID();

            // Insert order_items + mark beats sold (1 vente unique)
            foreach ($rows as $r) {
                $beatId = (int)$r['beat_id'];

                $fresh = $db->table('beats')->select('id, user_id, title, status, buyer_id, price')
                    ->where('id', $beatId)->get()->getRowArray();

                if (!$fresh || $fresh['status'] !== 'active' || !empty($fresh['buyer_id'])) {
                    throw new \RuntimeException("Le beat #$beatId vient d’être vendu/retiré.");
                }

                $priceCents = (int)round(((float)$fresh['price']) * 100);

                $db->table('order_items')->insert([
                    'order_id'    => $orderId,
                    'beat_id'     => $beatId,
                    'seller_id'   => (int)$fresh['user_id'],
                    'beat_title'  => $fresh['title'],
                    'price_cents' => $priceCents,
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);

                // Ajouter aux infos de l'événement
                $orderItems[] = [
                    'beatId'      => $beatId,
                    'sellerId'    => (int)$fresh['user_id'],
                    'beat_title'  => $fresh['title'],
                    'price_cents' => $priceCents,
                ];
            }

            // Clear cart
            $itemModel->clearCart($cartId);
            $db->table('carts')->where('id', $cartId)->update(['updated_at' => date('Y-m-d H:i:s')]);

            $db->transCommit();

            // ===== PATTERN OBSERVER =====
            // Créer l'événement d'achat terminé
            $event = new AchatTermineEvent($orderId, $userId, $orderItems);

            // Créer le dispatcher et enregistrer les observateurs
            $dispatcher = new EventDispatcher();
            $dispatcher->attach(new InventoryManager());
            $dispatcher->attach(new NotificationService());

            // Notifier tous les observateurs
            $dispatcher->notify($event);
            // ===== FIN PATTERN OBSERVER =====

        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/cart')->with('error', 'Checkout impossible : ' . $e->getMessage());
        }

        // rediriger vers la page Merci en affichant l'orderId
        return redirect()->to('/checkout/thanks/' . $orderId)->with('success', 'Achat effectué !');
    }

    /**
     * Page "Merci pour votre paiement"
     * GET /checkout/thanks/{orderId}
     *
     * - vérifie login
     * - vérifie ownership (orders.user_id) + status paid
     * - charge résumé (items, total, date)
     * - renvoie la vue cart/thanks.php
     */
    public function thanks(int $orderId)
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        $isLoggedIn = (bool)session()->get('isLoggedIn');

        if (!$isLoggedIn || $userId <= 0) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour accéder à cette page.');
        }

        $db = db_connect();

        $order = $db->table('orders')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->get()
            ->getRowArray();

        if (!$order) {
            return redirect()->to('/cart')->with('error', 'Commande introuvable ou accès refusé.');
        }

        $items = $db->table('order_items oi')
            ->select('oi.beat_id, oi.beat_title, oi.price_cents')
            ->where('oi.order_id', $orderId)
            ->orderBy('oi.id', 'ASC')
            ->get()
            ->getResultArray();

        return view('cart/thanks', [
            'title' => 'Merci pour votre paiement',
            'order' => $order,
            'items' => $items,
        ]);
    }

    // Récupère ou crée le panier de l'utilisateur connecté.
    private function getOrCreateCartId(): array
    {
        $session = session();
        $userId = (int)($session->get('user_id') ?? 0);

        if ($userId <= 0 || !(bool)$session->get('isLoggedIn')) {
            throw new \RuntimeException('Accès panier sans authentification.');
        }

        $db = db_connect();

        $userCart = $db->table('carts')->where('user_id', $userId)->get()->getRowArray();

        if (!$userCart) {
            $db->table('carts')->insert([
                'user_id'     => $userId,
                'guest_token' => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $userCart = $db->table('carts')->where('user_id', $userId)->get()->getRowArray();
        }

        return [(int)$userCart['id'], true];
    }

    // Vide complètement le panier.
    public function clear()
    {
        if (!(bool)session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Connecte-toi pour vider le panier.');
        }

        [$cartId] = $this->getOrCreateCartId();
        $itemModel = new \App\Models\CartItemModel();
        $itemModel->clearCart($cartId);

        return redirect()->to('/cart')->with('success', 'Le panier a été vidé.');
    }
}
