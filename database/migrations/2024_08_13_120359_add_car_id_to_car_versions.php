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
        Schema::table('car_versions', function (Blueprint $table) {
            $table->bigInteger('car_id')->index()->after('id');
            $table->tinyInteger('is_base_varient')->deafult(2)->after('car_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_versions', function (Blueprint $table) {
            //
        });
    }
};
