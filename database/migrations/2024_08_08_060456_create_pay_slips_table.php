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
        Schema::create('pay_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_schedule_id');
            $table->foreignId('employee_id');
            $table->decimal('basic_rate', total: 10, places: 2);
            $table->decimal('teaching_rate', total: 10, places: 2);
            $table->decimal('total_hours', total: 10, places: 2)->default(0);
            $table->decimal('working_hours', total: 10, places: 2)->default(0);
            $table->decimal('teaching_hours', total: 10, places: 2)->default(0);
            $table->decimal('total_teaching_hours', total: 10, places: 2)->default(0);
            $table->decimal('absent_days', total: 10, places: 2)->default(0);
            $table->decimal('absent_amount', total: 10, places: 2)->default(0);
            $table->decimal('tardiness_hours', total: 10, places: 2)->default(0);
            $table->decimal('tardiness_amount', total: 10, places: 2)->default(0);
            $table->decimal('overtime_hours', total: 10, places: 2)->default(0);
            $table->decimal('overtime_amount', total: 10, places: 2)->default(0);
            $table->decimal('holiday_hours', total: 10, places: 2)->default(0);
            $table->decimal('holiday_amount', total: 10, places: 2)->default(0);
            $table->decimal('gross_salary', total: 10, places: 2)->default(0);
            $table->json('included_additionals')->nullable();
            $table->json('not_included_additionals')->nullable();
            $table->json('taxable_deductions')->nullable();
            $table->json('not_taxable_deductions')->nullable();
            $table->decimal('wtax', total: 10, places: 2)->default(0);
            $table->decimal('sss', total: 10, places: 2)->default(0);
            $table->decimal('philhealth', total: 10, places: 2)->default(0);
            $table->decimal('pagibig', total: 10, places: 2)->default(0);
            $table->decimal('net_salary', total: 10, places: 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_slips');
    }
};
