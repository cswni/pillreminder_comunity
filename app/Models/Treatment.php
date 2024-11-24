<?php

namespace App\Models;

use App\Enums\TreatmentFirstRoute;
use App\Enums\TreatmentLocation;
use App\Enums\TreatmentVialType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Treatment extends Model
{
    //
    protected $fillable = [
        'user_id',
        'medicine_id',
        'dosage',
        'frequency',
        'start_date',
        'end_date',
        'vial_type',
        'custom_vial_type',
        'alternate_route',
        'first_route',
        'notify_feedback',
        'notify_pain',
        'is_active',
        'location',
        'custom_location',
    ];
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
            'frequency' => 'integer',
            'vial_type' => TreatmentVialType::class,
            'location' => TreatmentLocation::class,
            'first_route' => TreatmentFirstRoute::class,
            'first_route' => TreatmentFirstRoute::class,
        ];
    }
    /**
     * Get the Events that the Treatment has many.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
    /**
     * Get the Medicine that the Treatment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
    /**
     * Get the user that owns the Treatment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function latestEvent(): HasOne
    {
        return $this->hasOne(Event::class)->latestOfMany();
    }
}
