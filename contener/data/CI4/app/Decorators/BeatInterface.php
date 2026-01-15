<?php

namespace App\Decorators;

// Interface définissant les méthodes pour un beat.
interface BeatInterface {
    public function getTitle(): string;
    public function getPrice(): float;
    public function getCssClass(): string;
    public function getData(): array; // Pour accéder aux autres champs (bpm, genre, etc.)
}