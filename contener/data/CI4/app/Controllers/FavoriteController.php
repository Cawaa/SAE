<?php

namespace App\Controllers;

use App\Models\FavoriteModel;
use App\Models\BeatModel;
use CodeIgniter\Exceptions\PageNotFoundException;

// Contrôleur pour la gestion des favoris des utilisateurs.
class FavoriteController extends BaseController
{
    /**
     * GET /favorites
     */
    public function index()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        // Récupère les favoris de l'utilisateur en base de données.
        $rows = $this->db->table('favorites f')
            ->select('b.*, f.created_at AS favorited_at, c.name AS category_name, u.username AS seller_username')
            ->join('beats b', 'b.id = f.beat_id')
            ->join('categories c', 'c.id = b.category_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->where('f.user_id', $userId)
            ->orderBy('f.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('favorites/index', [
            'title' => 'Mes favoris',
            'favorites' => $rows,
        ]);
    }

    // Toggle le statut favori d'un beat pour l'utilisateur connecté.
    public function toggle(int $beatId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        // Vérifie que le beat existe
        $beatModel = new BeatModel();
        $beat = $beatModel->find($beatId);
        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        // Toggle le favori
        $favModel = new FavoriteModel();
        $isNowFav = $favModel->toggle($userId, $beatId);

        $msg = $isNowFav ? 'Ajouté aux favoris.' : 'Retiré des favoris.';
        return redirect()->back()->with('success', $msg);
    }

    // ajoute un beat aux favoris de l'utilisateur connecté.
    public function add(int $beatId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $favModel = new FavoriteModel();
        if (!$favModel->isFavorite($userId, $beatId)) {
            $favModel->toggle($userId, $beatId);
        }

        return redirect()->back()->with('success', 'Ajouté aux favoris.');
    }

    // retire un beat des favoris de l'utilisateur connecté.
    public function remove(int $beatId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $favModel = new FavoriteModel();
        if ($favModel->isFavorite($userId, $beatId)) {
            $favModel->toggle($userId, $beatId);
        }

        return redirect()->back()->with('success', 'Retiré des favoris.');
    }
}
