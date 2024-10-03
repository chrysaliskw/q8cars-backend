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
            $table->bigInteger('brand_id')->after('id');
            $table->text('key_feature_1')->after('title')->nullable();
            $table->text('key_feature_2')->after('key_feature_1')->nullable();
            $table->string('key_icon_1')->after('key_feature_2')->nullable();
            $table->string('key_icon_2')->after('key_icon_1')->nullable();
            $table->text('html_description')->after('description')->nullable();
            $table->string('view_count')->after('end_date')->default(0);
            $table->string('show_in_suggestions')->after('view_count')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            //
        });
    }
};
