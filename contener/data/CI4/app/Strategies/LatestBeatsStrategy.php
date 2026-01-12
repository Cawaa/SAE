<?php

namespace App\Strategies;

use CodeIgniter\Database\BaseBuilder;

class LatestBeatsStrategy implements BeatListStrategy
{
    public function apply(BaseBuilder $builder): BaseBuilder
    {
        return $builder
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->orderBy('b.created_at', 'DESC');
    }
}
