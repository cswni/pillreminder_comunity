<?php

namespace App\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TreatmentVialType: string implements HasLabel
{
    //
    case Oral = "oral";
    case Injection = "injection";
    case Other = "other";

    public static function all (): array{
        return array_map(fn($status) => $status->value, TreatmentVialType::cases());
    }
    public function getLabel(): string
    {
        return match ($this) {
            self::Oral => 'Oral',
            self::Injection => 'Injection',
            self::Other => 'Other',
        };
    }
}
