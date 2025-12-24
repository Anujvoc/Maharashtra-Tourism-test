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
        Schema::create('site_visit_reports', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('application');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('workflow_log_id')->nullable()->constrained('application_workflow_logs')->onDelete('cascade');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visit_reports');
    }
};
