<?php

namespace App\Strategies;

use CodeIgniter\Database\BaseBuilder;

class CheapestBeatsStrategy implements BeatListStrategy
{
    public function apply(BaseBuilder $builder): BaseBuilder
    {
        return $builder
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->orderBy('b.price', 'ASC')
            ->orderBy('b.created_at', 'DESC');
    }
}
