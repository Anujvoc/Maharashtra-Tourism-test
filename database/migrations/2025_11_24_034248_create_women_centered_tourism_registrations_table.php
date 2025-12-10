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
        Schema::create('women_centered_tourism_registrations', function (Blueprint $table) {
        $table->id();
        $table->enum('status', ['draft','submitted','approved','rejected','pending'])->default('pending');
        $table->boolean('is_apply')->default(false);
        $table->timestamp('submitted_at')->nullable();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('registration_id')->unique()->nullable();
        $table->string('slug_id')->unique();
        $table->string('application_form_id')->nullable();

        $table->string('email');
        $table->string('mobile', 20);
        $table->string('business_name')->nullable();
        $table->string('organisation_type')->nullable(); // sole, partnership, etc.
        $table->string('applicant_name')->nullable();
        $table->enum('gender', ['male', 'female'])->nullable();
        $table->date('dob')->nullable();
        $table->unsignedInteger('age')->nullable();
        $table->string('landline', 20)->nullable();
        $table->text('residential_address')->nullable();
        $table->text('business_address')->nullable();

        $table->string('tourism_business_type')->nullable();
        $table->string('tourism_business_name')->nullable();
        $table->string('aadhar_no', 20)->nullable();
        $table->string('pan_no', 20)->nullable();
        $table->string('company_pan_no', 20)->nullable();
        $table->string('caste')->nullable();
        $table->boolean('has_udyog_aadhar')->default(false);
        $table->string('udyog_aadhar_no')->nullable();
        $table->string('gst_no')->nullable();
        $table->unsignedInteger('female_employees')->nullable();
        $table->unsignedInteger('total_employees')->nullable();
        $table->decimal('total_project_cost', 15, 2)->nullable();
        $table->text('project_information')->nullable();

        // Bank details
        $table->string('bank_account_holder')->nullable();
        $table->string('bank_account_no')->nullable();
        $table->string('bank_account_type')->nullable();
        $table->string('bank_name')->nullable();
        $table->string('bank_ifsc')->nullable();

        $table->string('applicant_image_path')->nullable();
        $table->string('applicant_signature_path')->nullable();

        // Operation status
        $table->boolean('business_in_operation')->default(false);
        $table->date('business_operation_since')->nullable();
        $table->date('business_expected_start')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('women_centered_tourism_registrations');
    }
};
