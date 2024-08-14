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
        Schema::create('philhealths', function (Blueprint $table) {
            $table->id();
            $table->integer('bracket');
            $table->float('start_range');
            $table->float('end_range')->nullable();
            $table->float('base')->default(0);
            $table->float('premium')->default(0);
            $table->float('employee_share')->default(0);
            $table->float('employer_share')->default(0);
            $table->float('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('philhealths');
    }
};
