<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum TreatmentFirstRoute: string implements HasLabel
{
    case Left = 'left';
    case Right = 'right';
    case Indifferent = 'indifferent';

    public static function all (): array{
        return array_map(fn($status) => $status->value, TreatmentFirstRoute::cases());
    }
    public function getLabel(): string
    {
        return match ($this) {
            self::Left => 'Left',
            self::Right => 'Right',
            self::Indifferent => 'Indifferent',
        };
    }
}
