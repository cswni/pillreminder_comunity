<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum TreatmentLocation: string implements HasLabel
{
    //
    case Arms = 'arms';
    case Legs = 'legs';
    case Chest = 'chest';
    case Abdomen = 'abdomen';
    case Other = 'other';

    public static function all (): array{
        return array_map(fn($status) => $status->value, TreatmentLocation::cases());
    }
    public function getLabel(): string
    {
        return match ($this) {
            self::Arms => 'Arms',
            self::Legs => 'Legs',
            self::Chest => 'Chest',
            self::Abdomen => 'Abdomen',
            self::Other => 'Other'
        };
    }
}
