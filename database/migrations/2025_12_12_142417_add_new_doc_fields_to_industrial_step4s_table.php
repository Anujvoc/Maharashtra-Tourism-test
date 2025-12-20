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
        Schema::table('industrial_step4s', function (Blueprint $table) {
            if (!Schema::hasColumn('industrial_step4s', 'extra_doc_path')) {
                $table->string('extra_doc_path')->nullable()->after('building_cert_path');
            }

            if (!Schema::hasColumn('industrial_step4s', 'trade_license_path')) {
                $table->string('trade_license_path')->nullable()->after('extra_doc_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('industrial_step4s', function (Blueprint $table) {
            $table->dropColumn([
                'extra_doc_path',
                'trade_license_path',
            ]);
        });
    }
};
