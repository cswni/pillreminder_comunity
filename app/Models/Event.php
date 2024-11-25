<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Event extends Model
{
    //
    protected $fillable = [
        'medicine_id',
        'treatment_id',
        'user_id',
        'notified',
        'route',
        'grade_pain',
        'feedback',
        'scheduled_at'
    ];
    protected $cast = [
        'scheduled_at' => 'date',
    ];
    /**
     * Get the user that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the treatment that the Event belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }
    /**
     * Get the medicine that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
    /**
     * Generates a collection of events scheduled for a given treatment.
     * @return \Illuminate\Support\Collection
     * 
     * @param \App\Models\Treatment $treatment The treatment for which to generate events.
     * 
     * @param \App\Models\Event|null $latestEvent The last recorded event, if any.
     *
     * @param int $max The maximum number of events to generate. Default is 10
     */
    public static function calculateEvents(Treatment $treatment, ?Event $latestEvent = null, int $max = 10): Collection
    {
        //Treatment
        $start_date = Carbon::parse($treatment->start_date);
        $end_date = Carbon::parse($treatment->end_date);
        $frequency = $treatment->frequency * 60;

        //LatestEvent
        $latestEventDate = $latestEvent ? Carbon::parse($latestEvent->scheduled_at) : null;

        //General
        $index = $latestEvent ? ($latestEvent->index + 1) : 1;
        $indexLocal = 1;
        $events = collect();

        $start_date = $latestEvent ? $latestEventDate : $start_date;

        while ($indexLocal <= $max && $start_date <= $end_date) {
            $start_date->addMinutes($frequency);
            $event = collect([
                'treatment_id' => $treatment->id,
                'user_id' => $treatment->user_id,
                'scheduled_at' => $start_date->copy(),
                'index' => $index
            ]);
            $events->push($event);
            $indexLocal++;
            $index++;
        }

        return $events;
    }


}
