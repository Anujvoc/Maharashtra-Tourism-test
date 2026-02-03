<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('application'); // application_type, application_id
            $table->string('document_key'); // e.g., 'pan_card_path'
            $table->string('document_label')->nullable(); // e.g., 'PAN Card'
            $table->string('file_path')->nullable();
            $table->json('role_approvals')->nullable(); // stores { 'Clerk': { 'status': 'Approved', 'remark': null } }
            $table->string('overall_status')->default('Pending'); // Pending, Approved, Clarification
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
