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
        Schema::create('application_details', function (Blueprint $t) {
            $t->id();
            $t->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('name')->nullable();
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->string('business_name')->nullable();
            $t->string('business_type')->nullable();
            $t->string('pan')->nullable();
            $t->string('business_pan')->nullable();
            $t->string('aadhaar')->nullable();
            $t->string('udyam')->nullable();
            $t->string('ownership_proof')->nullable(); // file path
            $t->boolean('is_property_rented')->default(false);
            $t->string('operator_name')->nullable();
            $t->string('rental_agreement')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_details');
    }
};
