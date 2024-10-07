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
        Schema::create('emi_infos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('car_id')->index();
            $table->bigInteger('car_version_id')->index();
            $table->decimal('min_down_payment', 10,2);
            $table->decimal('interest_rate', 3,2);
            $table->decimal('principal_loan_amount', 10,2);
            $table->decimal('loan_tenure_year', 10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emi_infos');
    }
};
