<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTaxIncomesTableAddCompoundIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_incomes', function (Blueprint $table) {
            $table->index(['country_id', 'taxed_amount'], 'country_amount_compound_index');
            $table->index(['state_id', 'taxed_amount'], 'state_amount_compound_index');
            $table->index(['county_id', 'taxed_amount'], 'county_amount_compound_index');

            $table->index(['country_id', 'tax_category', 'taxed_amount'], 'country_amount_by_category_compound_index');
            $table->index(['state_id', 'tax_category', 'taxed_amount'], 'state_amount_by_category_compound_index');
            $table->index(['county_id', 'tax_category', 'taxed_amount'], 'county_amount_by_category_compound_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_incomes', function (Blueprint $table) {
            $table->dropIndex('country_amount_compount_index');
            $table->dropIndex('state_amount_compount_index');
            $table->dropIndex('county_amount_compount_index');

            $table->dropIndex('country_amount_by_category_compound_index');
            $table->dropIndex('state_amount_by_category_compound_index');
            $table->dropIndex('county_amount_by_category_compound_index');
        });
    }
}
