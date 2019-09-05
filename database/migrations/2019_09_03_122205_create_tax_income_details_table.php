<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxIncomeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_income_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tax_income_id')->index();
            $table->unsignedInteger('tax_rate_id')->index();
            $table->decimal('amount', 17, 2);
            $table->timestamps();

            $table->foreign('tax_income_id')->references('id')->on('tax_incomes');
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_income_details');
    }
}
