<?php

namespace App\Decorators;

// Classe de base pour les beats.
class BaseBeat implements BeatInterface {
    protected array $data;

    // Constructeur qui initialise les données du beat.
    public function __construct(array $data) {
        $this->data = $data;
    }

    // Méthodes pour obtenir les informations du beat.
    public function getTitle(): string { return $this->data['title'] ?? 'Sans titre'; }
    public function getPrice(): float { return (float)($this->data['price'] ?? 0); }
    // Retourne la classe CSS associée au beat.
    public function getCssClass(): string { return "beat-card"; }
    public function getData(): array { return $this->data; }
}