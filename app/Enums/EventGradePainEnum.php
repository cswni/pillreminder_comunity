<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum EventGradePainEnum: string implements HasLabel
{
    //
    case Mild = 'mild';
    case Moderate = 'moderate';
    case Severe = 'severe';
    public static function all(): array
    {
        return array_map(fn($status) => $status->value, EventGradePainEnum::cases());
    }
    public function getLabel(): string
    {
        return match ($this) {
            self::Mild => 'Mild',
            self::Moderate => 'Moderate',
            self::Severe => 'Severe',
        };
    }
}
