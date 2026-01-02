<?php

namespace App\Controllers;

use App\Models\BeatModel;

class BoutiqueController extends BaseController
{
    /**
     * Alias possible d'une page "boutique" (sinon tu peux ignorer ce controller)
     */
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
