<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');

            /**
             * note that depending on the country, income tax can be country wide, state wide or county wide.
             * Some country does not have state at all, and some does not have county.
             * Logically we can use tax rates depending on the data in this table.
             */
            $table->unsignedInteger('country_id')->nullable()->index();
            $table->unsignedInteger('state_id')->nullable()->index();
            $table->unsignedInteger('county_id')->nullable()->index();

            $table->double(6, 4);
            $table->text('note');
            $table->timestamps();

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
        Schema::dropIfExists('tax_rates');
    }
}
