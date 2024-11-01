<?php

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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('dosage');
            $table->integer('frequency');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->enum('vial_type', ['oral', 'injection','other']);
            $table->string('custom_vial_type')->nullable();
            $table->enum('location', ['arms', 'legs', 'chest', 'abdomen', 'other']);
            $table->string('custom_location')->nullable();
            $table->boolean('alternate_route')->default(false);
            $table->enum('first_route', ['left', 'right', 'indifferent'])->nullable();
            $table->boolean('notify_feedback')->default(false);
            $table->boolean('notify_pain')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
