<?php

use App\Enums\EventGradePainEnum;
use App\Enums\EventStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', EventStatusEnum::all())->default(EventStatusEnum::Pending);
            $table->dropColumn(['grade_pain']);
            $table->enum('grade_pain', EventGradePainEnum::all())->nullable();
            $table->timestamp('scheduled_at');
            $table->integer('index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
            $table->dropColumn(['status', 'grade_pain', 'scheduled_at', 'index']);
            $table->integer('grade_pain')->nullable();
        });
    }
};
