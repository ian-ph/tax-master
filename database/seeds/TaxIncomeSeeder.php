<?php

use Illuminate\Database\Seeder;
use App\Country;
use App\State;
use App\County;
use App\TaxIncome;

class TaxIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = 5000;

        $country = Country::where('country_code', 'USA')->first();
        $states = State::where('country_id', $country->id)->get();
        $counties = County::where('country_id', $country->id)->get();
        $countyKeys = $counties->pluck('id')->toArray();
        $this->command->getOutput()->progressStart($limit);
        for ($i=1; $i<=$limit; $i++) {
            //pick a random county
            $countyIdKey    = array_rand($countyKeys);
            $county = $counties->where('id', $countyKeys[$countyIdKey])->first();

            $taxIncome = new TaxIncome;

            $taxIncome->uuid        = Str::uuid();
            $taxIncome->country_id  = $country->id;
            $taxIncome->state_id    = $county->state_id;
            $taxIncome->county_id   = $county->id;

            $taxIncome->amount = rand(10000, 5000000.99);
            $taxIncome->filing_date = date('2017-06-11');
            $taxIncome->tax_category = rand(1, 2);
            $taxIncome->taxed_amount = 0;
            $taxIncome->save();

            $taxIncome->taxed_amount = $taxIncome->compute();
            $taxIncome->save();
            $this->command->getOutput()->progressAdvance();
           /*
            $taxIncome->uuid        = Str::uuid();
            $taxIncome->country_id  = $country->id;
            $taxIncome->state_id    = 495;
            $taxIncome->county_id   = 6500;

            $taxIncome->amount = 2000000;
            $taxIncome->filing_date = date('2017-06-11');
            $taxIncome->tax_category = 2;
            $taxIncome->taxed_amount = 0;
            $taxIncome->save();

            $taxIncome->taxed_amount = $taxIncome->compute();
            $taxIncome->save();
            */
        }
        $this->command->getOutput()->progressFinish();
    }
}
