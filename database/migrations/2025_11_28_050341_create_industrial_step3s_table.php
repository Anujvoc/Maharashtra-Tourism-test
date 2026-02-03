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
        Schema::create('industrial_step3s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_form_id')->nullable();
            $table->string('slug_id');

            // Bathroom
            $table->boolean('bath_attached')->default(false);
            $table->boolean('bath_hot_cold')->default(false);
            $table->boolean('water_saving_taps')->default(false);

            // Public area
            $table->boolean('public_lobby')->default(false);
            $table->boolean('reception')->default(false);
            $table->boolean('public_restrooms')->default(false);

            // Differently abled
            $table->boolean('disabled_room')->default(false);

            // Kitchen / food prod
            $table->boolean('fssai_kitchen')->default(false);

            // Staff
            $table->boolean('uniforms')->default(false);

            // Code of conduct
            $table->boolean('pledge_display')->default(false);
            $table->boolean('complaint_book')->default(false);
            $table->boolean('nodal_officer')->default(false);

            // Guest services
            $table->boolean('doctor_on_call')->default(false);

            // Safety & security
            $table->boolean('police_verification')->default(false);
            $table->boolean('fire_drills')->default(false);
            $table->boolean('first_aid')->default(false);

            // Additional features
            $table->boolean('suite')->default(false);
            $table->boolean('fb_outlet')->default(false);
            $table->boolean('iron_facility')->default(false);
            $table->boolean('paid_transport')->default(false);
            $table->boolean('business_center')->default(false);
            $table->boolean('conference_facilities')->default(false);
            $table->boolean('sewage_treatment')->default(false);
            $table->boolean('rainwater_harvesting')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industrial_step3s');
    }
};
