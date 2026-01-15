<?php

namespace App\Controllers;

// Contrôleur pour les pages légales.
class LegalController extends BaseController
{
    // Affiche la page des mentions légales.
    public function mentions()
    {
        return view('legal/mentions', [
            'title' => 'Mentions légales'
        ]);
    }

    // Affiche la page de la politique de confidentialité.
    public function privacy()
    {
        return view('legal/privacy', [
            'title' => 'Politique de confidentialité'
        ]);
    }

    // Affiche la page des conditions générales de vente.
    public function cgv()
    {
        return view('legal/cgv', [
            'title' => 'Conditions générales de vente'
        ]);
    }
}
