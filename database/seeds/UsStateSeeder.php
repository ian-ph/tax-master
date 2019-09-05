<?php

use Illuminate\Database\Seeder;
use App\Country;

class UsStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert($this->data());
    }

    private function data()
    {
        $countryId = Country::where('country_code', 'USA')->first()->id;
        return [
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'District of Columbia', 'state_code'=>'DC'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'American Samoa', 'state_code'=>'AS'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Guam', 'state_code'=>'GU'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Northern Mariana Islands', 'state_code'=>'MP'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Puerto Rico', 'state_code'=>'PR'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'U.S. Virgin Islands', 'state_code'=>'VI'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Alabama', 'state_code'=>'AL'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Alaska', 'state_code'=>'AK'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Arizona', 'state_code'=>'AZ'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Arkansas', 'state_code'=>'AR'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'California', 'state_code'=>'CA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Colorado', 'state_code'=>'CO'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Connecticut', 'state_code'=>'CT'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Delaware', 'state_code'=>'DE'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Florida', 'state_code'=>'FL'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Georgia', 'state_code'=>'GA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Hawaii', 'state_code'=>'HI'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Idaho', 'state_code'=>'ID'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Illinois', 'state_code'=>'IL'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Indiana', 'state_code'=>'IN'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Iowa', 'state_code'=>'IA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Kansas', 'state_code'=>'KS'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Kentucky', 'state_code'=>'KY'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Louisiana', 'state_code'=>'LA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Maine', 'state_code'=>'ME'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Maryland', 'state_code'=>'MD'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Massachusetts', 'state_code'=>'MA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Michigan', 'state_code'=>'MI'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Minnesota', 'state_code'=>'MN'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Mississippi', 'state_code'=>'MS'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Missouri', 'state_code'=>'MO'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Montana', 'state_code'=>'MT'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Nebraska', 'state_code'=>'NE'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Nevada', 'state_code'=>'NV'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'New Hampshire', 'state_code'=>'NH'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'New Jersey', 'state_code'=>'NJ'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'New Mexico', 'state_code'=>'NM'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'New York', 'state_code'=>'NY'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'North Carolina', 'state_code'=>'NC'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'North Dakota', 'state_code'=>'ND'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Ohio', 'state_code'=>'OH'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Oklahoma', 'state_code'=>'OK'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Oregon', 'state_code'=>'OR'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Pennsylvania', 'state_code'=>'PA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Rhode Island', 'state_code'=>'RI'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'South Carolina', 'state_code'=>'SC'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'South Dakota', 'state_code'=>'SD'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Tennessee', 'state_code'=>'TN'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Texas', 'state_code'=>'TX'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Utah', 'state_code'=>'UT'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Vermont', 'state_code'=>'VT'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Virginia', 'state_code'=>'VA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Washington', 'state_code'=>'WA'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'West Virginia', 'state_code'=>'WV'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Wisconsin', 'state_code'=>'WI'],
            ['country_id'=>$countryId, 'uuid'=>Str::uuid(), 'state_name'=>'Wyoming', 'state_code'=>'WY'],
        ];
    }
}
