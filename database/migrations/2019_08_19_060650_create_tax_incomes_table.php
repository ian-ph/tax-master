<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->double('amount');

            //unnormalized to optimize query performace when this table becomes huge
            $table->unsignedInteger('country_id')->index();
            $table->unsignedInteger('state_id')->index();
            $table->unsignedInteger('county_id')->index();

            //longer timespan of data can have different rates
            $table->unsignedInteger('taxt_rate_id')->index();

            $table->timestamps();

            $table->foreign('taxt_rate_id')->references('id')->on('tax_rates');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('county_id')->references('id')->on('counties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_incomes');
    }
}
