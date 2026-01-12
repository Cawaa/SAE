<?php

namespace App\Controllers;

use App\Strategies\BeatFeedService;
use App\Strategies\LatestBeatsStrategy;
use App\Strategies\CheapestBeatsStrategy;

class HomeController extends BaseController
{
    public function index(): string
    {
        $sort = (string) ($this->request->getGet('sort') ?? 'latest');

        $service = new BeatFeedService($this->db);

        $strategy = match ($sort) {
            'cheap', 'cheapest', 'price' => new CheapestBeatsStrategy(),
            default => new LatestBeatsStrategy(),
        };

        // Home : tu avais 6 éléments, on garde 6
        $beats = $service->getBeats($strategy, 6);

        return view('home', [
            'title' => 'Accueil',
            'beats' => $beats,
            'sort'  => $sort,
        ]);
    }
}
