<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Treatment;
use Carbon\Carbon;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Filament\Forms;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Actions;
use Filament\Support\Facades\FilamentColor;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Event::class;
    protected static ?int $sort = 2;

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $userId = Auth::id();
        // $colors = Color::all();
        $colors = FilamentColor::getColors();
        $treatments = Treatment::with([
            'events:id,treatment_id,scheduled_at,index',
            'medicine:id,name'
        ])
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->select('id', 'medicine_id', 'start_date', 'end_date')
            ->get();
        $arrayColorsEvents = [];
        $usedColors = [];

        $tones = [400, 600, 800];
        $events = collect();
        foreach ($treatments as $treatment) {
            foreach ($tones as $tone) {
                foreach ($colors as $key => $color) {
                    if (isset($color[$tone])) {
                        $rgb = 'rgb(' . $color[$tone] . ')';
                        if (!in_array($rgb, $usedColors) && !isset($arrayColorsEvents[$treatment->id])) {
                            $usedColors[] = $rgb;
                            $arrayColorsEvents[$treatment->id] = $rgb;
                            $movedColor = array_shift($colors);
                            array_push($colors, $movedColor);
                            continue 2;
                        }
                    }
                }
            }
            foreach ($treatment->events as $event) {
                $events->push(
                    EventData::make()
                        ->id($event->id)
                        ->start($event->scheduled_at)
                        ->end(Carbon::parse($event->scheduled_at)->addSecond()->format('Y-m-d H:i:s'))
                        ->title($treatment->medicine->name)
                        ->borderColor($arrayColorsEvents[$treatment->id])
                );
            }
        }
        return $events->toArray();
    }
    protected function headerActions(): array
    {
        return [
            // Actions\CreateAction::make(), //Cancelamos Evento de creacion
        ];
    }
    protected function viewAction(): Action
    {
        return Actions\ViewAction::make()
            ->mutateRecordDataUsing(function (array $data): array {
                return [
                    ...$data,
                    'treatment-start_date' => $this->record->treatment->start_date,
                    'treatment-end_date' => $this->record->treatment->end_date,
                    'treatment-medicine-name' => $this->record->treatment->medicine->name,
                ];
            });
    }
    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
        ];
    }
    public function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Information Treatment')
                ->schema([
                    Forms\Components\DateTimePicker::make('treatment-start_date')
                        ->label('treatment start date')
                        ->disabled(),
                    Forms\Components\DateTimePicker::make('treatment-end_date')
                        ->label('treatment end date')
                        ->disabled(),
                    Forms\Components\TextInput::make('treatment-medicine-name')
                        ->label('Medicine name')
                        ->disabled(),
                ])->columns(2),
            Forms\Components\Section::make('Information Event')
                ->schema([
                    Forms\Components\DateTimePicker::make('scheduled_at')
                        ->label('scheduled at'),
                    Forms\Components\TextInput::make('index')
                        ->label('Event number')
                        ->numeric()
                        ->prefix('#')
                ])->columns(2),
        ];
    }
}
