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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('registration_id')->unique()->nullable();
            $table->string('slug_id')->unique();
            $table->string('application_form_id')->nullable();
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->enum('status', ['draft','submitted','approved','rejected'])->default('draft');
            $table->boolean('is_apply')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
