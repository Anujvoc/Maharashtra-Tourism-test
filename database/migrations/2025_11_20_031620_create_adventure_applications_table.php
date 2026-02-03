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
        Schema::create('adventure_applications', function (Blueprint $table) {
        $table->id();
        $table->string('email')->unique()->nullable();
        $table->string('mobile')->unique()->nullable();
        $table->string('applicant_type')->nullable();
        $table->string('applicant_name')->nullable();
        $table->string('company_name')->nullable();
        $table->text('applicant_address')->nullable();
        $table->unsignedBigInteger('region_id')->nullable();
        $table->unsignedBigInteger('district_id')->nullable();
        $table->string('whatsapp')->nullable();

        $table->string('adventure_category')->nullable();
        $table->string('activity_name')->nullable();
        $table->text('activity_location')->nullable();

        $table->string('pan_file')->nullable();
        $table->string('aadhar_file')->nullable();

        $table->enum('status', ['submitted','approved','rejected','pending'])->default('pending');

        $table->boolean('is_apply')->default(false);
        $table->timestamp('submitted_at')->nullable();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->string('registration_id')->unique()->nullable();
        $table->string('slug_id')->unique();
        $table->string('application_form_id')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adventure_applications');
    }
};
