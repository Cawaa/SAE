<?php

namespace App\Controllers;

use App\Models\ArtistSpotlightModel;

// Contrôleur pour la page des artistes.
class ArtistController extends BaseController
{
    public function index()
    {
        // Récupère les artistes en vedette.
        $spot = new ArtistSpotlightModel();

        $topSellers = $spot->getTopSellers(8);
        $sellerIds  = array_map(fn($x) => (int)$x['user_id'], $topSellers);
        $soldBeats  = $spot->getSoldBeatsForUsers($sellerIds, 6);

        $topPosters = $spot->getTopPosters(8);
        $posterIds  = array_map(fn($x) => (int)$x['user_id'], $topPosters);
        $availBeats = $spot->getAvailableBeatsForUsers($posterIds, 6);

        return view('artists/index', [
            'topSellers' => $topSellers,
            'soldBeats'  => $soldBeats,
            'topPosters' => $topPosters,
            'availBeats' => $availBeats,
        ]);
    }
}
