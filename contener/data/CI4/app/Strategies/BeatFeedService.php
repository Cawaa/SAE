<?php

namespace App\Strategies;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\BaseBuilder;
use Config\Database;

class BeatFeedService
{
    private ConnectionInterface $db;

    public function __construct(?ConnectionInterface $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getBeats(BeatListStrategy $strategy, int $limit = 12, int $offset = 0): array
    {
        $builder = $this->baseBeatsBuilder();
        $builder = $strategy->apply($builder);

        return $builder
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    private function baseBeatsBuilder(): BaseBuilder
    {
        return $this->db->table('beats b')
            ->select('b.id, b.title, b.price, b.bpm, b.musical_key, b.created_at, b.user_id');
    }
}
