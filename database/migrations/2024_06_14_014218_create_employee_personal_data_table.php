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
        Schema::create('employee_personal_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('tin')->nullable();
            $table->string('sss')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('pag_ibig')->nullable();
            $table->string('gsis')->nullable();
            $table->string('crn')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('residential_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('spouse_full_name')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_mobile_no')->nullable();
            $table->string('spouse_occupation_employer')->nullable();
            $table->string('spouse_occupation_business_address')->nullable();
            $table->string('father_full_name')->nullable();
            $table->string('father_mobile_no')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_occupation_employer')->nullable();
            $table->string('father_occupation_business_address')->nullable();
            $table->string('mother_full_name')->nullable();
            $table->string('mother_mobile_no')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_occupation_employer')->nullable();
            $table->string('mother_occupation_business_address')->nullable();
            $table->string('emergency_contact_full_name')->nullable();
            $table->string('emergency_contact_address')->nullable();
            $table->string('emergency_contact_mobile_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_personal_data');
    }
};
