<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use App\Models\Event;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTreatment extends CreateRecord
{
    protected static string $resource = TreatmentResource::class;
    protected ?bool $hasDatabaseTransactions = true;

    protected function handleRecordCreation(array $data): Model
    {
        $treatment = static::getModel()::create($data);
        $dataEvent = Event::calculateEvents($treatment);
        $firstEvent = $dataEvent->first();
        if ($firstEvent) {
            $now = Carbon::now();
            $scheduled_at = Carbon::parse($firstEvent['scheduled_at']);

            $diffInHours = $now->diffInHours($scheduled_at, false);
            $diffInMinutes = $now->diffInMinutes($scheduled_at, false);

            if ($diffInHours >= 0) {
                $hours = floor(abs($diffInMinutes) / 60);
                $minutes = abs($diffInMinutes) % 60;
                if ($hours < 24) {
                    $message = "Faltan $hours horas y $minutes minutos para el evento.";
                } else {
                    $message = "El evento está programado para el " . $scheduled_at->translatedFormat('d \d\e F \a \l\a\s H:i');
                }
            } else {
                $message = "El evento ya pasó.";
            }
            Notification::make()
                ->title('New event created')
                ->body($message)
                ->success()
                ->send();
        }

        Event::insert($dataEvent->toArray());
        return $treatment;
    }
}
