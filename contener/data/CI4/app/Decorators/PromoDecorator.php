<?php

namespace App\Decorators;

// Décorateur pour appliquer une promotion de 20% sur le prix du beat.
class PromoDecorator extends BeatDecorator {
    public function getTitle(): string {
        return "🔥 PROMO : " . $this->beat->getTitle();
    }

    // Applique une réduction de 20% sur le prix.
    public function getPrice(): float {
        return round($this->beat->getPrice() * 0.8, 2); // -20%
    }

    public function getCssClass(): string {
        return $this->beat->getCssClass() . " border-promo";
    }
}