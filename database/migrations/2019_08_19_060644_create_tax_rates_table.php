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

            // Un-normalized for faster queries
            $table->unsignedInteger('country_id')->nullable()->index();
            $table->unsignedInteger('state_id')->nullable()->index();
            $table->unsignedInteger('county_id')->nullable()->index();

            $table->decimal('bracket_minimum', 17, 2)->nullable();
            $table->decimal('bracket_maximum', 17, 2)->nullable();
            $table->decimal('rate_percentage', 6, 4)->default(0);
            $table->decimal('rate_fixed', 17, 2)->default(0);
            $table->unsignedTinyInteger('tax_type')->default(1);
            $table->unsignedTinyInteger('tax_category')->default(1);
            $table->text('note')->nullable();

            $table->date('implementation_date');
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
