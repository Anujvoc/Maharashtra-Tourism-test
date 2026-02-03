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
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'current_stage')) {
                $table->string('current_stage')->default('Clerk')->nullable()->after('status');
            }
            if (!Schema::hasColumn('applications', 'workflow_status')) {
                $table->string('workflow_status')->default('Pending')->nullable()->after('current_stage');
            }
            if (!Schema::hasColumn('applications', 'region_id')) {
                $table->unsignedInteger('region_id')->nullable()->after('workflow_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            //
        });
    }
};
