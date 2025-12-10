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
        Schema::create('industrial_step4s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_form_id')->nullable();
            $table->string('slug_id');

            $table->string('mseb_consumer_no')->nullable();
            $table->string('star_category')->nullable();

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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industrial_step4s');
    }
};
