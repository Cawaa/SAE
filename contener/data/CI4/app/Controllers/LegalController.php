<?php

namespace App\Controllers;

class LegalController extends BaseController
{
    public function mentions()
    {
        return view('legal/mentions', [
            'title' => 'Mentions légales'
        ]);
    }

    public function privacy()
    {
        return view('legal/privacy', [
            'title' => 'Politique de confidentialité'
        ]);
    }

    public function cgv()
    {
        return view('legal/cgv', [
            'title' => 'Conditions générales de vente'
        ]);
    }
}
