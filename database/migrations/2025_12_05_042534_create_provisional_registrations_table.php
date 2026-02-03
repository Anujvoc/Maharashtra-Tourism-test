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
        Schema::create('provisional_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('application_form_id')->nullable();
            $table->string('registration_id')->unique();
            $table->string('slug_id')->unique();


            $table->boolean('is_apply')->default(true);
            $table->timestamp('submitted_at')->nullable();

            // Step 1: General Details
            $table->string('applicant_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('enterprise_type')->nullable();
            $table->string('aadhar_number', 12)->nullable();
            $table->string('application_category')->nullable();

            // Step 2: Project Details
            $table->json('site_address')->nullable(); // JSON containing address fields
            $table->string('udyog_aadhar')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('zone')->nullable();
            $table->string('project_type')->nullable();
            $table->json('expansion_details')->nullable(); // JSON for expansion table data
            $table->json('entrepreneurs_profile')->nullable(); // JSON for entrepreneurs table
            $table->string('project_category')->nullable();
            $table->string('other_category')->nullable();
            $table->string('project_subcategory')->nullable();
            $table->text('project_description')->nullable();

              // Step 3: Investment Details
              $table->decimal('land_area', 10, 2)->nullable();
              $table->string('land_ownership_type')->nullable();
              $table->string('building_ownership_type')->nullable();
              $table->decimal('project_cost', 15, 2)->nullable();
              $table->integer('total_employees')->nullable();
              $table->json('investment_components')->nullable(); // JSON for investment table

              // Step 4: Means of Finance
              $table->json('means_of_finance')->nullable(); // JSON for finance table

              // Step 5: Enclosures
              $table->json('enclosures')->nullable(); // JSON for enclosures
              $table->json('other_documents')->nullable(); // JSON for other documents
                // Step 6: Declaration
            $table->boolean('declaration_accepted')->default(false);
            $table->string('place')->nullable();
            $table->date('date')->nullable();
            $table->string('signature_path')->nullable();

            // Progress tracking
            $table->integer('current_step')->default(1);
            $table->json('progress')->nullable(); // ['done' => 1, 'total' => 6]
            $table->boolean('is_completed')->default(false);

            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved','pending', 'rejected'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provisional_registrations');
    }
};
