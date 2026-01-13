<?php

namespace App\Decorators;

class BaseBeat implements BeatInterface {
    protected array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function getTitle(): string { return $this->data['title'] ?? 'Sans titre'; }
    public function getPrice(): float { return (float)($this->data['price'] ?? 0); }
    public function getCssClass(): string { return "beat-card"; }
    public function getData(): array { return $this->data; }
}