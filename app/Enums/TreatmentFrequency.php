<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum TreatmentFrequency: int implements HasLabel
{
    case Every4Hours = 4;
    case Every6Hours = 6;
    case Every8Hours = 8;
    case Every12Hours = 12;
    case Every24Hours = 24;

    public static function all (): array{
        return array_map(fn($status) => $status->value, TreatmentFrequency::cases());
    }
    public static function minHours() : int {
        return 4;
    }
    public static function maxHours() : int {
        return 120;
    }
    public static function helperText() : string {
        $minHours = self::minHours();
        $maxHours = self::maxHours();
        return "Enter the frequency in hours (e.g., every $minHours hours). Must be between $minHours and $maxHours hours.";
    }
    public function getLabel(): string
    {
        return match ($this) {
            self::Every4Hours => 'Every 4 hours',
            self::Every6Hours => 'Every 6 hours',
            self::Every8Hours => 'Every 8 hours',
            self::Every12Hours => 'Every 12 hours',
            self::Every24Hours => 'Every 24 hours',
        };
    }

}
