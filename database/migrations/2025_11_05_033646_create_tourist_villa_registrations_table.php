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
        Schema::create('tourist_villa_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained() ->onDelete('set null');
            $table->string('applicant_name', 120)->nullable();
            $table->string('applicant_phone', 20)->nullable();
            $table->string('applicant_email', 120)->nullable();
            $table->string('business_name', 120)->nullable();
            $table->string('business_type', 40)->nullable();
            $table->string('pan_number', 10)->nullable();
            $table->string('business_pan_number', 10)->nullable();
            $table->string('aadhar_number', 20)->nullable();
            $table->string('udyam_aadhar_number', 30)->nullable();
            $table->string('ownership_proof', 60)->nullable();
            $table->boolean('property_rented')->default(false);
            $table->string('operator_name', 120)->nullable();
            $table->string('rental_agreement_path')->nullable(); // file

            // B) Property
            $table->string('property_name', 120)->nullable();
            $table->text('property_address')->nullable();
            $table->string('address_proof', 60)->nullable();
            $table->string('property_coordinates', 255)->nullable();
            $table->boolean('property_operational')->default(false);
            $table->unsignedSmallInteger('operational_year')->nullable();
            $table->unsignedInteger('guests_hosted')->nullable();
            $table->unsignedInteger('total_area')->default(0);
            $table->string('mahabooking_number', 40)->nullable();

            // C) Accommodation
            $table->unsignedSmallInteger('number_of_rooms')->nullable();
            $table->unsignedInteger('room_area')->nullable();
            $table->boolean('attached_toilet')->default(false);
            $table->boolean('dustbins')->default(false);
            $table->boolean('road_access')->default(false);
            $table->boolean('food_provided')->default(false);
            $table->boolean('payment_options')->default(false);

            // D) Facilities (store as JSON array of keys)
            $table->json('facilities')->nullable();

            // E) GRAS
            $table->boolean('application_fees')->default(false);
            $table->boolean('status')->default(false);
            $table->string('gras_certificate_path')->nullable(); // file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourist_villa_registrations');
    }
};
