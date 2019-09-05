<?php

use Illuminate\Database\Seeder;
use App\County;
use App\TaxRate;

class CountyTaxIncomeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $counties = County::all();

        foreach ($counties as $county) {
            $taxRate = [
                'uuid' => Str::uuid(),
                'county_id' => $county->id,
                'state_id' => $county->state_id,
                'bracket_minimum' => 0,
                'rate_percentage' => rand(2, 10),
                'note' => 'single',
                'tax_type' => 1,
                'tax_category' => 1,
                'country_id' => $county->country_id,
                'implementation_date' => '2016-01-01'
            ];

            DB::table('tax_rates')->insert($taxRate);

            $taxRate = [
                'uuid' => Str::uuid(),
                'county_id' => $county->id,
                'state_id' => $county->state_id,
                'bracket_minimum' => 0,
                'rate_percentage' => rand(2, 10),
                'note' => 'joint',
                'tax_type' => 1,
                'tax_category' => 2,
                'country_id' => $county->country_id,
                'implementation_date' => '2016-01-01'
            ];

            DB::table('tax_rates')->insert($taxRate);
        }
    }
}
