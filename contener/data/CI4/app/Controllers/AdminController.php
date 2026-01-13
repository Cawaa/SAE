<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BeatModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    /**
     * Vue unique d'administration : liste les utilisateurs et les annonces.
     */
    public function index(): string
    {
        $userModel = new \App\Models\UserModel();
        $beatModel = new \App\Models\BeatModel();

        $data = [
            'title' => 'Administration',
            'users' => $userModel->findAll(), // Récupère tous les utilisateurs
            'beats' => $beatModel->findAll()  // Récupère tous les beats
        ];

        return view('Admin/index', $data);
    }

    /**
     * Supprime un utilisateur.
     */
    public function deleteUser(int $id): ResponseInterface
    {
        $userModel = new UserModel();
        
        if ((int)session()->get('user_id') === $id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($userModel->delete($id)) {
            return redirect()->back()->with('success', 'Utilisateur supprimé.');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression.');
    }

    /**
     * Supprime une annonce (beat).
     */
    public function deleteBeat(int $id): ResponseInterface
    {
        $beatModel = new BeatModel();
        
        if ($beatModel->delete($id)) {
            return redirect()->back()->with('success', 'Annonce supprimée.');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression.');
    }
}