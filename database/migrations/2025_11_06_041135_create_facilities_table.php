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
        Schema::create('facilities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->boolean('kitchen')->default(false);
            $t->boolean('dining_hall')->default(false);
            $t->boolean('garden')->default(false);
            $t->boolean('parking')->default(false);
            $t->boolean('ev_charging')->default(false);
            $t->boolean('children_play_area')->default(false);
            $t->boolean('swimming_pool')->default(false);
            $t->boolean('wifi')->default(false);
            $t->boolean('first_aid')->default(false);
            $t->boolean('fire_safety')->default(false);
            $t->boolean('water_purifier')->default(false);
            $t->boolean('rainwater_harvesting')->default(false);
            $t->boolean('solar_power')->default(false);
            $t->boolean('other_renewable')->default(false);
            $t->boolean('gras_paid')->default(false);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
