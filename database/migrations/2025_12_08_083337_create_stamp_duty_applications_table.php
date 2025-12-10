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
        Schema::create('stamp_duty_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('registration_id')->unique()->nullable();
            $table->string('application_form_id')->nullable();
            $table->boolean('is_apply')->default(false);
            $table->string('slug_id')->unique();
            // Progress tracking
            $table->integer('current_step')->default(1);
            $table->json('progress')->nullable(); // ['done' => 1, 'total' => 6]
            $table->boolean('is_completed')->default(false);
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'pending', 'rejected'])
                  ->default('draft');
            $table->boolean('declaration_accepted')->default(false);

            // Region / District / State selection etc.
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();

            // Section A: General Details
            $table->string('company_name')->nullable();
            $table->string('registration_no')->nullable();
            $table->date('application_date')->nullable();
            $table->string('applicant_type')->nullable();
            $table->string('agreement_type')->nullable();

            // Correspondence Address
            $table->text('c_address')->nullable();
            $table->string('c_city')->nullable();
            $table->string('c_taluka')->nullable();
            $table->string('c_district')->nullable();
            $table->string('c_state')->nullable();
            $table->string('c_pincode')->nullable();
            $table->string('c_mobile')->nullable();
            $table->string('c_phone')->nullable();
            $table->string('c_email')->nullable();
            $table->string('c_fax')->nullable();

            // Project Site Address
            $table->text('p_address')->nullable();
            $table->string('p_city')->nullable();
            $table->string('p_taluka')->nullable();
            $table->string('p_district')->nullable();
            $table->string('p_state')->nullable();
            $table->string('p_pincode')->nullable();
            $table->string('p_mobile')->nullable();
            $table->string('p_phone')->nullable();
            $table->string('p_email')->nullable();
            $table->string('p_website')->nullable();

            // Additional Section A info
            $table->decimal('estimated_project_cost', 15, 2)->nullable();
            $table->integer('proposed_employment')->nullable();
            $table->text('tourism_activities')->nullable();
            $table->text('incentives_availed')->nullable();
            $table->boolean('existed_before')->default(false);
            $table->string('eligibility_cert_no')->nullable();
            $table->date('eligibility_date')->nullable();
            $table->text('present_status')->nullable();

            // Section B - Land & Built-up area
            $table->string('land_gat')->nullable();
            $table->string('land_village')->nullable();
            $table->string('land_taluka')->nullable();
            $table->string('land_district')->nullable();

            $table->decimal('area_a', 15, 2)->nullable();
            $table->decimal('area_b', 15, 2)->nullable();
            $table->decimal('area_c', 15, 2)->nullable();
            $table->decimal('area_d', 15, 2)->nullable();
            $table->decimal('area_e', 15, 2)->nullable();

            // Non-Agricultural land
            $table->string('na_gat')->nullable();
            $table->string('na_village')->nullable();
            $table->string('na_taluka')->nullable();
            $table->string('na_district')->nullable();
            $table->decimal('na_area', 15, 2)->nullable();

            // Project Cost (in lakhs)
            $table->decimal('cost_land', 15, 2)->nullable();
            $table->decimal('cost_building', 15, 2)->nullable();
            $table->decimal('cost_machinery', 15, 2)->nullable();
            $table->decimal('cost_electrical', 15, 2)->nullable();
            $table->decimal('cost_misc', 15, 2)->nullable();
            $table->decimal('cost_other', 15, 2)->nullable();

            $table->integer('project_employment')->nullable();
            $table->text('noc_purpose')->nullable();
            $table->text('noc_authority')->nullable();

            // Declaration
            $table->string('name_designation')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('stamp_path')->nullable();

            // Documents (store paths)
            $table->string('doc_challan')->nullable();
            $table->string('doc_affidavit')->nullable();
            $table->string('doc_registration')->nullable();
            $table->string('doc_ror')->nullable();
            $table->string('doc_land_map')->nullable();
            $table->string('doc_dpr')->nullable();
            $table->string('doc_agreement')->nullable();
            $table->string('doc_construction_plan')->nullable();
            $table->string('doc_dp_remarks')->nullable();

            // Affidavit fields
            $table->string('aff_name')->nullable();
            $table->string('aff_company')->nullable();
            $table->text('aff_registered_office')->nullable();
            $table->decimal('aff_land_area', 15, 2)->nullable();
            $table->string('aff_cts')->nullable();
            $table->string('aff_village')->nullable();
            $table->string('aff_taluka')->nullable();
            $table->string('aff_district')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_duty_applications');
    }
};
