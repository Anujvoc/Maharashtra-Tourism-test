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
        Schema::create('industrial_step2s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_form_id')->nullable();
            $table->string('slug_id');

            // General requirement checkboxes
            $table->boolean('min_rooms')->default(false);
            $table->boolean('room_size_ok')->default(false);
            $table->boolean('bathroom_size_ok')->default(false);
            $table->boolean('bathroom_fixtures')->default(false);
            $table->boolean('full_time_operation')->default(false);
            $table->boolean('elevators')->default(false);
            $table->boolean('electricity_availability')->default(false);
            $table->boolean('emergency_lights')->default(false);
            $table->boolean('cctv')->default(false);
            $table->boolean('disabled_access')->default(false);
            $table->boolean('security_guards')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industrial_step2s');
    }
};
