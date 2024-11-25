<?php

use App\Models\Event;
use App\Models\Treatment;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    $treatments = Treatment::where('is_active', true)->with('latestEvent')->get(['id','user_id', 'frequency', 'start_date', 'end_date']);
    foreach ($treatments as $treatment) {
        $events = Event::calculateEvents($treatment, $treatment->latestEvent)->toArray();
        Event::insert($events);
    }
})->daily();