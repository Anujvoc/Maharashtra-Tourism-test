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
        Schema::create('agriculture_registrations', function (Blueprint $table) {
        $table->id();
        $table->enum('status', ['submitted','approved','rejected','pending'])->default('pending');
        $table->boolean('is_apply')->default(false);
        $table->timestamp('submitted_at')->nullable();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->string('registration_id')->unique()->nullable();
        $table->string('slug_id')->unique();
        $table->string('application_form_id')->nullable();



         // Basic info
         $table->string('email');
         $table->string('mobile');
         $table->string('applicant_name');
         $table->string('center_name'); // Name of Agro Tourism Center
         $table->unsignedBigInteger('applicant_type_id')->nullable(); // FK to applicant type master (optional)

         $table->text('applicant_address');
         $table->text('center_address')->nullable();
         $table->unsignedBigInteger('region_id')->nullable();
         $table->unsignedBigInteger('district_id')->nullable();

         $table->text('land_description')->nullable();

         // Type of Agro-tourism Center - facilities (checkboxes)
         $table->boolean('facility_day_trip')->default(false);
         $table->boolean('facility_accommodation')->default(false);
         $table->boolean('facility_recreational_service')->default(false);
         $table->boolean('facility_play_area_children')->default(false);
         $table->boolean('facility_adventure_games')->default(false);
         $table->boolean('facility_rural_games')->default(false);
         $table->boolean('facility_agricultural_camping')->default(false);
         $table->boolean('facility_horticulture_product_sale')->default(false);

         // Does applicant live at place?
         $table->enum('applicant_live_in_place', ['yes','no'])->default('yes');

         // Other activities related to agriculture (checkboxes)
         $table->boolean('activity_green_house')->default(false);
         $table->boolean('activity_milk_business')->default(false);
         $table->boolean('activity_fisheries')->default(false);
         $table->boolean('activity_rop_vatika')->default(false);
         $table->boolean('activity_animal_bird_rearing')->default(false);
         $table->boolean('activity_nature_adventure_tourism')->default(false);
         $table->boolean('activity_other')->default(false);
         $table->string('activity_other_text')->nullable();

         // If already exists, when started
         $table->string('center_started_on')->nullable();

         // Files (paths)
         $table->string('file_signature_stamp')->nullable();
         $table->string('file_land_documents')->nullable();
         $table->string('file_registration_certificate')->nullable();
         $table->string('file_authorization_letter')->nullable();
         $table->string('file_pan_card')->nullable();
         $table->string('file_aadhar_card')->nullable();
         $table->string('file_registration_fee_challan')->nullable();
         $table->string('file_electricity_bill')->nullable();
         $table->string('file_food_security_licence')->nullable();
         $table->string('file_building_permission')->nullable();
         $table->string('file_declaration_form')->nullable();
         $table->string('file_zone_certificate')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agriculture_registrations');
    }
};
