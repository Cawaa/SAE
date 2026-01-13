<?php

namespace App\Decorators;

class PromoDecorator extends BeatDecorator {
    public function getTitle(): string {
        return "🔥 PROMO : " . $this->beat->getTitle();
    }

    public function getPrice(): float {
        return round($this->beat->getPrice() * 0.8, 2); // -20%
    }

    public function getCssClass(): string {
        return $this->beat->getCssClass() . " border-promo";
    }
}