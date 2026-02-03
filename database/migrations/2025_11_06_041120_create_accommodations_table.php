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
        Schema::create('accommodations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('flats_count');
            $t->json('flat_types'); // e.g. [{"type":"2BHK","area":900,"attached_toilet":true}]
            $t->boolean('has_dustbins')->default(true);
            $t->boolean('road_access')->default(true);
            $t->boolean('food_on_request')->default(false);
            $t->boolean('payment_cash')->default(false);
            $t->boolean('payment_upi')->default(false);
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
