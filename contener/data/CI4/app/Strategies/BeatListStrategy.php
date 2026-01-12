<?php

namespace App\Strategies;

use CodeIgniter\Database\BaseBuilder;

interface BeatListStrategy
{
    /**
     * Applique filtres/tri sur un builder déjà positionné sur beats (alias b).
     */
    public function apply(BaseBuilder $builder): BaseBuilder;
}
