<?php

namespace App\Models;

use CodeIgniter\Model;

// Modèle pour gérer les beats.
class BeatModel extends Model
{
    protected $table      = 'beats';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'user_id','category_id','bpm','musical_key','tags',
        'title','description','price',
        'status','buyer_id','sold_at',
        'is_featured','created_at','updated_at'
    ];

    protected $useTimestamps = false;

    // Récupère un beat actif non vendu par son ID.
    public function findActiveById(int $id): ?array
    {
        return $this->where('id', $id)
            ->where('status', 'active')
            ->where('buyer_id', null)
            ->first();
    }


    // Marque un beat comme vendu.
    public function markAsSold(int $beatId, int $buyerId): bool
    {
        $builder = $this->db->table($this->table);

        $builder->where('id', $beatId);
        $builder->where('status', 'active');
        $builder->where('buyer_id', null);

        $builder->update([
            'status'     => 'sold',
            'buyer_id'   => $buyerId,
            'sold_at'    => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->db->affectedRows() === 1;
    }

    /**
     * Feed par défaut (boutique sans recherche):
     * - beats actifs non vendus
     * - tri: featured puis récent
     */
    public function getDefaultFeed(int $limit = 30): array
    {
        return $this->db->table('beats b')
            ->select('b.*, c.name AS category_name, u.username AS seller_username')
            ->join('categories c', 'c.id = b.category_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->orderBy('b.is_featured', 'DESC')
            ->orderBy('b.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Un beat avec joins (page produit)
     */
    public function getOneWithJoins(int $id): ?array
    {
        $row = $this->db->table('beats b')
            ->select('b.*, c.name AS category_name, u.username AS seller_username')
            ->join('categories c', 'c.id = b.category_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->where('b.id', $id)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    /**
     * Recherche avancée — titre OU artiste (+ tags/description)
     * Filtres:
     * - q, category_id, bpm_min, bpm_max, price_min, price_max, musical_key
     */
    public function search(array $filters): array
    {
        $builder = $this->db->table('beats b')
            ->select('b.*, c.name AS category_name, u.username AS seller_username')
            ->join('categories c', 'c.id = b.category_id', 'left')
            ->join('users u', 'u.id = b.user_id', 'left')
            ->where('b.status', 'active')
            ->where('b.buyer_id', null);

        // q = recherche texte : titre OU artiste (+ tags/description)
        $q = trim((string)($filters['q'] ?? ''));
        if ($q !== '') {
            $builder->groupStart()
                ->like('b.title', $q)
                ->orLike('u.username', $q)
                ->orLike('b.tags', $q)
                ->orLike('b.description', $q)
            ->groupEnd();
        }

        // category_id
        if (!empty($filters['category_id'])) {
            $builder->where('b.category_id', (int)$filters['category_id']);
        }

        // bpm range
        if (!empty($filters['bpm_min'])) {
            $builder->where('b.bpm >=', (int)$filters['bpm_min']);
        }
        if (!empty($filters['bpm_max'])) {
            $builder->where('b.bpm <=', (int)$filters['bpm_max']);
        }

        // price range (ton controller les envoie, donc on les applique)
        if ($filters['price_min'] !== null && $filters['price_min'] !== '') {
            $builder->where('b.price >=', (float)$filters['price_min']);
        }
        if ($filters['price_max'] !== null && $filters['price_max'] !== '') {
            $builder->where('b.price <=', (float)$filters['price_max']);
        }

        // musical key
        if (!empty($filters['musical_key'])) {
            $builder->where('b.musical_key', (string)$filters['musical_key']);
        }

        // tri (comme ton feed)
        $builder->orderBy('b.is_featured', 'DESC');
        $builder->orderBy('b.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function findBySeller(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function findPurchasedByBuyer(int $userId): array
    {
        return $this->where('buyer_id', $userId)
            ->orderBy('sold_at', 'DESC')
            ->findAll();
    }
}
