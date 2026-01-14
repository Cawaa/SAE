<?php

namespace App\Controllers;

use App\Strategies\BeatFeedService;
use App\Strategies\LatestBeatsStrategy;
use App\Strategies\CheapestBeatsStrategy;

// Contrôleur pour la page d'accueil.
class HomeController extends BaseController
{
    // Affiche la page d'accueil avec les beats.
    public function index(): string
    {
        $sort = (string) ($this->request->getGet('sort') ?? 'latest');
        // Récupère les beats selon la stratégie de tri choisie.
        $service = new BeatFeedService($this->db);

        // Choisit la stratégie de tri
        $strategy = match ($sort) {
            'cheap', 'cheapest', 'price' => new CheapestBeatsStrategy(),
            default => new LatestBeatsStrategy(),
        };

        // Récupère les beats à afficher
        $beats = $service->getBeats($strategy, 6);

        return view('home', [
            'title' => 'Accueil',
            'beats' => $beats,
            'sort'  => $sort,
        ]);
    }
}
