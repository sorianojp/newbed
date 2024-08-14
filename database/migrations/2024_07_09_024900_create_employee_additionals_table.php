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
        Schema::create('employee_additionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_schedule_id');
            $table->foreignId('employee_id');
            $table->foreignId('additional_id');
            $table->float('amount');
            $table->string('remark')->nullable();
            $table->foreignId('encoded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_additionals');
    }
};
