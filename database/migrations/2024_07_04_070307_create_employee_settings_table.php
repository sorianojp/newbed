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
        Schema::create('employee_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->foreignId('payroll_type_id')->nullable();
            $table->float('monthly_basic_salary')->default(0);
            $table->integer('cut_off_days_per_month')->default(22);
            $table->float('hourly_rate')->default(0);
            $table->float('teaching_rate')->default(0);
            $table->string('computation_basis')->nullable();
            $table->string('salary_period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_settings');
    }
};
