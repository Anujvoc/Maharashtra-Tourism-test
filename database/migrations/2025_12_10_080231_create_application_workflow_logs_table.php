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
        Schema::create('application_workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('application');
            $table->string('stage')->index(); // Clerk, Asst Director, etc.
            $table->string('status')->index(); // Approved, Rejected, Clarification
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('remark')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_workflow_logs');
    }
};
