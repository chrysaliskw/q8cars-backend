<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('car_comparison_lists', function (Blueprint $table) {
            $table->string('body_type')->after('page');
        });
    }

    /**
     * Reverse the migrations.
     */ public function down()
    {
        Schema::table('car_comparison_lists', function (Blueprint $table) {
            $table->dropColumn('body_type');
        });
    }
};
