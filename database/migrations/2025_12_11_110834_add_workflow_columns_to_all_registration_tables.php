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
            'stamp_duty_applications',
            'adventure_applications',
            'tourism_apartments',
            'tourist_villa_registrations',
            'eligibility_registrations',
            'industrial_registrations',
            'agriculture_registrations',
            'women_centered_tourism_registrations',
            'caravan_registrations',
            'provisional_registrations',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'current_stage')) {
                        $table->string('current_stage')->default('Clerk')->nullable()->after('status');
                    }
                    if (!Schema::hasColumn($tableName, 'workflow_status')) {
                        $table->string('workflow_status')->default('Pending')->nullable()->after('current_stage');
                    }
                });
            }
        }
    }

    
    public function down(): void
    {
        Schema::table('all_registration_tables', function (Blueprint $table) {
            //
        });
    }
};
