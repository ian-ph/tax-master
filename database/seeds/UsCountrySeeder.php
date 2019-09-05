<?php

use Illuminate\Database\Seeder;

class UsCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'uuid' => Str::uuid(),
            'country_name' => 'United States of America',
            'country_code' => 'USA',
            'currency_code' => 'USD',
            'computation_type' => '2'
        ]);
    }
}
