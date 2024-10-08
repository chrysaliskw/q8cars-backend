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
        Schema::table('offers', function (Blueprint $table) {
            $table->text('html_key_feature_1')->nullable()->after('html_description');
            $table->text('html_key_feature_2')->nullable()->after('html_key_feature_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('html_key_feature_1');
            $table->dropColumn('html_key_feature_2');
        });
    }
};
