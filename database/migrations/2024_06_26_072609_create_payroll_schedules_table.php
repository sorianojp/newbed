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
        Schema::create('payroll_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_type_id')->constrained();
            $table->date('salary_start_date');
            $table->date('salary_end_date');
            $table->date('cutoff_start_date');
            $table->date('cutoff_end_date');
            $table->date('pay_date');
            $table->string('period');
            $table->string('month');
            $table->string('year');
            $table->string('status')->default('started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_schedules');
    }
};
