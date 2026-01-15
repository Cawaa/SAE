<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\FavoriteModel;
use App\Models\ConversationModel;
use App\Models\SubscriptionModel;
use App\Models\WalletModel;
use CodeIgniter\I18n\Time;

/**
 * Contrôleur de gestion du compte utilisateur.
 * Gère les opérations liées au profil, aux favoris, conversations, wallet et abonnements.
 */
class AccountController extends BaseController
{
    /**
     * Vérifie que l'utilisateur est connecté.
     * Redirige vers la page de connexion si non authentifié.
     * 
     * @return int|object L'ID utilisateur si authentifié, sinon une redirection
     */
    private function requireLogin()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }
        return $userId;
    }

    /**
     * Affiche le tableau de bord du compte avec les statistiques de l'utilisateur.
     * Récupère le nombre de beats, favoris, conversations et infos de wallet.
     */
    public function index()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Récupération des statistiques simples
        $db = db_connect();

        $beatsTotal  = (int) $db->table('beats')->where('user_id', $userId)->countAllResults();
        $beatsActive = (int) $db->table('beats')->where(['user_id' => $userId, 'status' => 'active'])->countAllResults();
        $beatsSold   = (int) $db->table('beats')->where(['user_id' => $userId, 'status' => 'sold'])->countAllResults();

        $favoritesCount = (int) $db->table('favorites')->where('user_id', $userId)->countAllResults();

        $convModel = new ConversationModel();
        $conversationsCount = count($convModel->listForUser($userId));

        $walletModel = new WalletModel();
        $wallet = $walletModel->where('user_id', $userId)->first();

        $subModel = new SubscriptionModel();
        $subscription = $subModel->getAnyActive($userId);

        return view('account/index', [
            'title' => 'Mon compte',
            'user'  => $user,
            'stats' => [
                'beats_total'         => $beatsTotal,
                'beats_active'        => $beatsActive,
                'beats_sold'          => $beatsSold,
                'favorites_count'     => $favoritesCount,
                'conversations_count' => $conversationsCount,
            ],
            'wallet' => $wallet,
            'subscription' => $subscription,
        ]);
    }

    public function profile()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        return view('account/profile', [
            'title' => 'Mon profil',
            'user'  => $user,
            'error' => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
        ]);
    }

     /**
     * Met à jour le profil utilisateur (genre artistique et avatar).
     * Valide les fichiers d'avatar et les stocke dans /images/avatars/
     */

    public function updateProfile()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $genre = trim((string) $this->request->getPost('artist_genre'));

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if (!$user) {
            return redirect()->to('/account')->with('error', 'Utilisateur introuvable.');
        }

        $data = [
            'artist_genre' => $genre !== '' ? $genre : null,
        ];

        // Avatar (optionnel)
        // En DB: 'avatars/<filename>'
        $file = $this->request->getFile('avatar');
        if ($file && $file->isValid() && !$file->hasMoved() && $file->getSize() > 0) {

            $mime = (string) $file->getMimeType();
            $size = (int) $file->getSize();

            if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)) {
                return redirect()->to('/account/profile')->with('error', 'Avatar invalide (jpg/png/webp/gif).');
            }
            if ($size > 5 * 1024 * 1024) {
                return redirect()->to('/account/profile')->with('error', 'Avatar trop volumineux (max 5MB).');
            }

            $avatarDir = rtrim(\FCPATH, '/\\') . '/images/avatars';
            if (!is_dir($avatarDir)) {
                @mkdir($avatarDir, 0775, true);
            }

            if (!is_dir($avatarDir) || !is_writable($avatarDir)) {
                return redirect()->to('/account/profile')->with('error', 'Dossier avatar non accessible (permissions serveur).');
            }

            $newName = $file->getRandomName();
            $file->move($avatarDir, $newName, true);

            // Supprimer l'ancien avatar si c'est un fichier uploadé (avatars/*)
            $old = (string)($user['avatar'] ?? '');
            if ($old !== '' && str_starts_with($old, 'avatars/')) {
                $old = str_replace(['..', '\\'], ['', '/'], $old);
                $oldAbs = rtrim(\FCPATH, '/\\') . '/images/' . ltrim($old, '/');
                if (is_file($oldAbs)) {
                    @unlink($oldAbs);
                }
            }

            $data['avatar'] = 'avatars/' . $newName;
        }

        $userModel->update($userId, $data);

        return redirect()->to('/account/profile')->with('success', 'Profil mis à jour.');
    }

    // Affiche la liste des beats favoris de l'utilisateur.
     
    public function favorites()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $db = db_connect();
        $favorites = $db->table('favorites f')
            ->select('b.id, b.title, b.price, b.bpm, b.musical_key, b.status, b.buyer_id, f.created_at')
            ->join('beats b', 'b.id = f.beat_id')
            ->where('f.user_id', $userId)
            ->orderBy('f.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('account/favorites', [
            'title' => 'Mes favoris',
            'favorites' => $favorites,
        ]);
    }

    // Affiche la liste des conversations de l'utilisateur.
    public function conversations()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $convModel = new ConversationModel();
        $conversations = $convModel->listForUser($userId);

        return view('account/conversations', [
            'title' => 'Mes conversations',
            'conversations' => $conversations,
            'userId' => $userId,
        ]);
    }

    // Redirige vers la liste des beats de l'utilisateur.
    public function beatsIndex()
    {
        return redirect()->to('/my/beats');
    }

    /**
     * Redirige vers le formulaire de création de beat.
     */
    public function beatCreateForm()
    {
        return redirect()->to('/beats/create');
    }

    public function beatCreate()
    {
        return redirect()->to('/beats/create');
    }

    public function beatEditForm($id)
    {
        return redirect()->to("/beats/{$id}/edit");
    }

    public function beatUpdate($id)
    {
        return redirect()->to("/beats/{$id}/edit");
    }

    public function beatDelete($id)
    {
        return redirect()->to("/beats/{$id}/delete");
    }

    public function wallet()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $walletModel = new WalletModel();
        $wallet = $walletModel->where('user_id', $userId)->first();

        // Liste des achats: plus récent -> plus ancien
        $db = db_connect();
        $purchases = $db->table('orders o')
            ->select('o.id AS order_id, o.paid_at, o.total_cents, oi.beat_id, oi.beat_title, oi.price_cents')
            ->join('order_items oi', 'oi.order_id = o.id')
            ->where('o.user_id', $userId)
            ->where('o.status', 'paid')
            ->orderBy('o.paid_at', 'DESC')
            ->orderBy('oi.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('account/wallet', [
            'title'     => 'Wallet',
            'wallet'    => $wallet,
            'purchases' => $purchases,
        ]);
    }

    public function subscription()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $subModel = new SubscriptionModel();
        $subscription = $subModel->getAnyActive($userId);

        return view('account/subscription', [
            'title' => 'Abonnement',
            'subscription' => $subscription,
        ]);
    }

    public function moderation()
    {
        $this->requireLogin();
        return view('account/moderation', [
            'title' => 'Modération',
        ]);
    }

    public function buySubscription()
    {
        $userId = $this->requireLogin();
        if (!is_int($userId)) return $userId;

        $type = (string) $this->request->getPost('type');
        if ($type === '') {
            $type = 'premium';
        }

        // Simulé : on active un abonnement 30 jours
        $subModel = new SubscriptionModel();

        $now = Time::now();
        $end = $now->addDays(30);

        // Si déjà actif de ce type, on prolonge
        $existing = $subModel->getActive($userId, $type);
        if ($existing) {
            $currentEnd = !empty($existing['ends_at']) ? Time::parse($existing['ends_at']) : $now;
            $newEnd = $currentEnd->isAfter($now) ? $currentEnd->addDays(30) : $end;

            $subModel->update((int)$existing['id'], [
                'ends_at' => $newEnd->toDateTimeString(),
            ]);

            return redirect()->to('/account/subscription')
                ->with('success', "Abonnement prolongé (+30 jours).");
        }

        // Sinon on crée un abonnement actif
      
        $subModel->insert([
            'user_id' => $userId,
            'type' => $type,
            'status' => 'active',
            // valeurs “MVP”
            'commission_percent' => 0,
            'buyer_discount_percent' => 0,
            'monthly_credit_cents' => 0,
            'started_at' => $now->toDateTimeString(),
            'ends_at' => $end->toDateTimeString(),
        ]);

        return redirect()->to('/account/subscription')
            ->with('success', "Abonnement activé (simulation, 30 jours).");
    }
}
