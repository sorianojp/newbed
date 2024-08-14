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
        Schema::table('employee_settings', function (Blueprint $table) {
            $table->string('tax')->nullable()->default('TRAIN')->after('salary_period');
            $table->float('tax_percentage')->default(0)->after('salary_period');
            $table->boolean('sss')->default(0)->after('salary_period');
            $table->boolean('pag_ibig')->default(0)->after('salary_period');
            $table->boolean('phil_health')->default(0)->after('salary_period');
            $table->boolean('holiday_pay')->default(0)->after('salary_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_settings', function (Blueprint $table) {
            $table->dropColumn('tax');
            $table->dropColumn('tax_percentage');
            $table->dropColumn('sss');
            $table->dropColumn('pag_ibig');
            $table->dropColumn('phil_health');
            $table->dropColumn('holiday_pay');
        });
    }
};
