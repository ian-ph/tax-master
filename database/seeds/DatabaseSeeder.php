<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsCountrySeeder::class,
            UsStateSeeder::class,
            UsCountySeeder::class,
            UsStateIncomeTaxSeeder::class,
            CountyTaxIncomeRateSeeder::class,
            TaxIncomeSeeder::class
        ]);
    }
}
