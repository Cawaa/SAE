<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BeatModel;

// Contrôleur pour la page des artistes.
class ArtistController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $beatModel = new BeatModel();
        $db = \Config\Database::connect();

        // Récupérer les paramètres de recherche
        $search = $this->request->getGet('search') ?? '';
        $sort = $this->request->getGet('sort') ?? 'popular';

        // Requête de base pour récupérer les artistes avec leurs stats
        $builder = $db->table('users u')
            ->select('u.id, u.username, u.avatar, u.artist_genre, u.created_at')
            ->select('(SELECT COUNT(*) FROM beats WHERE user_id = u.id AND status = "active") as beats_count')
            ->select('(SELECT COUNT(*) FROM beats WHERE user_id = u.id AND buyer_id IS NOT NULL) as sold_beats_count');

        // Filtre de recherche
        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.username', $search)
                ->orLike('u.artist_genre', $search)
                ->groupEnd();
        }

        // Tri
        switch ($sort) {
            case 'recent':
                $builder->orderBy('u.created_at', 'DESC');
                break;
            case 'name':
                $builder->orderBy('u.username', 'ASC');
                break;
            case 'popular':
            default:
                $builder->orderBy('sold_beats_count', 'DESC');
                break;
        }

        $artists = $builder->get()->getResultArray();

        return view('artists/index', [
            'artists' => $artists,
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    public function show($userId)
    {
        $userModel = new UserModel();
        $beatModel = new BeatModel();

        // Récupérer les informations de l'artiste
        $artist = $userModel->find($userId);

        if (!$artist) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Artiste non trouvé");
        }

        // Récupérer tous les beats de l'artiste (disponibles)
        $availableBeats = $beatModel
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where('buyer_id', null)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Récupérer les beats vendus
        $soldBeats = $beatModel
            ->where('user_id', $userId)
            ->where('buyer_id IS NOT NULL', null, false)
            ->orderBy('sold_at', 'DESC')
            ->findAll();

        return view('artists/show', [
            'artist' => $artist,
            'availableBeats' => $availableBeats,
            'soldBeats' => $soldBeats,
        ]);
    }
}
