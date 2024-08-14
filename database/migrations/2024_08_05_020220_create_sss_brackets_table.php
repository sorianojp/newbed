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
        Schema::create('sss_brackets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sss_id');
            $table->float('start_range')->nullable();
            $table->float('end_range')->nullable();
            $table->float('msc');
            $table->float('ec');
            $table->float('er');
            $table->float('ee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sss_brackets');
    }
};
