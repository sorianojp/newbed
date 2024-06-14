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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('name_ext')->nullable();
            $table->date('birthdate');
            $table->string('mobile_no');
            $table->string('personal_email');
            $table->foreignId('position_id')->constrained('positions');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('tenureship_id')->constrained('tenureships');
            $table->decimal('base_salary', 9, 3);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
