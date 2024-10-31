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
        Schema::table('banks', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('city');
            $table->decimal('base_gross_income')->nullable()->default(0)->after('logo');
            $table->decimal('base_other_emi')->nullable()->default(0)->after('base_gross_income');
            $table->decimal('base_interest_rate')->nullable()->default(0)->after('base_other_emi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('base_gross_income');
            $table->dropColumn('base_other_emi');
            $table->dropColumn('base_interest_rate');
        });
    }
};
