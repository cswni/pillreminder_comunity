<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum EventStatusEnum: string implements HasLabel
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Canceled = 'canceled';

    public static function all(): array
    {
        return array_map(fn($status) => $status->value, EventStatusEnum::cases());
    }
    
    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Completed => 'Completed',
            self::Canceled => 'Canceled',
        };
    }
}
