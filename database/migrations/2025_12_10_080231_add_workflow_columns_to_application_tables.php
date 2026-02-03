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
        $tables = [
            'tourist_villa_registrations',
            'adventure_applications',
            'agriculture_registrations',
            'women_centered_tourism_registrations',
            'industrial_registrations',
            'provisional_registrations',
            'eligibility_registrations',
            'stamp_duty_applications',
            'tourism_apartments',
            'caravan_registrations'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'current_stage')) {
                        $table->string('current_stage')->default('Clerk')->nullable()->after('id');
                    }
                    if (!Schema::hasColumn($tableName, 'workflow_status')) {
                        $table->string('workflow_status')->default('Pending')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'region_id')) {
                        $table->unsignedInteger('region_id')->nullable();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_tables', function (Blueprint $table) {
            //
        });
    }
};
