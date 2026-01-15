<?php

namespace App\Decorators;

// Classe abstraite pour les décorateurs de beats.
abstract class BeatDecorator implements BeatInterface {
    protected BeatInterface $beat;

    public function __construct(BeatInterface $beat) {
        $this->beat = $beat;
    }

    public function getTitle(): string { return $this->beat->getTitle(); }
    public function getPrice(): float { return $this->beat->getPrice(); }
    public function getCssClass(): string { return $this->beat->getCssClass(); }
    public function getData(): array { return $this->beat->getData(); }
}