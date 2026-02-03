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
        Schema::create('eligibility_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('registration_id')->unique()->nullable();
            $table->string('application_form_id')->nullable();
            $table->boolean('is_apply')->default(false);
            $table->string('slug_id')->unique();
            $table->string('applicant_name')->nullable();
            $table->string('provisional_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();

            $table->json('entrepreneurs')->nullable();

            $table->text('project_description')->nullable();

            $table->date('commencement_date')->nullable();
            $table->string('operation_details')->nullable();

            $table->json('cost_component')->nullable();
            $table->json('asset_age')->nullable();
            $table->json('ownership')->nullable();

            $table->json('enclosures')->nullable();
            $table->json('other_docs')->nullable();

            $table->string('signature_path')->nullable();
            $table->string('declaration_place')->nullable();
            $table->date('declaration_date')->nullable();
            $table->enum('status', ['submitted','approved','rejected','pending'])->default('pending');
            $table->timestamp('submitted_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eligibility_registrations');
    }
};
