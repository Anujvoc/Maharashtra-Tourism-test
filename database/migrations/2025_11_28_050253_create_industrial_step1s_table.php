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
        Schema::create('industrial_step1s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_form_id')->nullable();
            $table->string('slug_id');        // sabhi step tables mein same slug_id

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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industrial_step1s');
    }
};
