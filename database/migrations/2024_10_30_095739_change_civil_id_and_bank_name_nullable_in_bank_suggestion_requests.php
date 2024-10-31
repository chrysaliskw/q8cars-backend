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
        Schema::table('bank_suggestion_requests', function (Blueprint $table) {
            $table->string('civil_id')->nullable()->change();
            $table->string('bank_name')->nullable()->change();
            $table->string('contact_number')->nullable()->after('last_name');
            $table->tinyInteger('type')->after('status');
            $table->bigInteger('bank_id')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_suggestion_requests', function (Blueprint $table) {
            $table->string('civil_id')->nullable(false)->change();
            $table->string('bank_name')->nullable(false)->change();
            $table->dropColumn('type');
            $table->dropColumn('bank_id');
            $table->dropColumn('contact_number');
        });
    }
};
