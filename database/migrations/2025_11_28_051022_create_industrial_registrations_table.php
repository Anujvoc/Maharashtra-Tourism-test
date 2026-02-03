<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('industrial_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_form_id')->nullable();

            $table->string('registration_id')->unique();
            $table->string('slug_id')->unique();

            $table->enum('status', ['draft','submitted','approved','rejected','pending'])
                  ->default('submitted');
            $table->boolean('is_apply')->default(true);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->string('email');
            $table->string('mobile', 20);
            $table->string('hotel_name');
            $table->text('hotel_address');
            $table->string('company_name');
            $table->text('company_address');

            $table->string('authorized_person')->nullable();
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('applicant_type')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('total_area', 10, 2)->nullable();
            $table->integer('total_employees')->nullable();
            $table->integer('total_rooms')->nullable();
            $table->date('commencement_date')->nullable();
            $table->string('emergency_contact', 20)->nullable();

            $table->string('mseb_consumer_number')->nullable();
            $table->string('star_category')->nullable();
            $table->text('electricity_company')->nullable();
            $table->text('property_tax_dept')->nullable();
            $table->text('water_bill_dept')->nullable();

            // docs
            $table->string('pan_card_path')->nullable();
            $table->string('aadhaar_card_path')->nullable();
            $table->string('gst_cert_path')->nullable();
            $table->string('fssai_cert_path')->nullable();
            $table->string('business_reg_path')->nullable();
            $table->string('declaration_path')->nullable();
            $table->string('mpcb_cert_path')->nullable();
            $table->string('light_bill_path')->nullable();
            $table->string('fire_noc_path')->nullable();
            $table->string('property_tax_path')->nullable();
            $table->string('star_cert_path')->nullable();
            $table->string('water_bill_path')->nullable();
            $table->string('electricity_bill_path')->nullable();
            $table->string('building_cert_path')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industrial_registrations');
    }
};
