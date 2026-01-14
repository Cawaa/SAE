<?php

namespace App\Controllers;

use App\Models\BeatModel;

// Contrôleur pour la page de la boutique.
class BoutiqueController extends BaseController
{
    
    // Affiche la liste des beats disponibles dans la boutique.
    public function index()
    {
        $beatModel = new BeatModel();
        $beats = $beatModel->search([]);

        return view('beats/index', [
            'title' => 'Boutique',
            'beats' => $beats,
            'filters' => [],
        ]);
    }
}
