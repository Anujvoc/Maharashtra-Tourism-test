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
        Schema::create('property_details', function (Blueprint $t) {
            $t->id();
            $t->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('property_name')->nullable();
            $t->text('address')->nullable();
            $t->string('address_proof')->nullable(); 
            $t->string('geo_link')->nullable();
            $t->boolean('is_operational')->default(false);
            $t->string('operational_since')->nullable();
            $t->integer('guests_till_march')->nullable();
            $t->integer('total_area_sqft')->nullable();
            $t->string('mahabooking_reg_no')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_details');
    }
};
