<?php

use Illuminate\Database\Seeder;
use App\State;
use App\Country;

class UsStateIncomeTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * We parse our data gathered from wikipedia.
         */
        $country = Country::where('country_code', 'USA')->first();
        $states = State::all();
        $data = (str_getcsv($this->data(), "\n"));
        foreach ($data as $d) {
            $a = str_getcsv($d);
            $array[] = $a;
        }

        $taxRates = [];
        foreach ($array as $taxData) {
            $state = $states->where('state_name', trim($taxData[0]))->first();

            for ($i = 1; $i<= count($taxData)-1; $i++) {
                if ($taxData[$i] == 'none') {
                    continue;
                }

                $rate = explode(' > ', $taxData[$i]);
                if (empty($rate[1])) {
                    continue;
                }
                $taxRate = [
                    'uuid' => Str::uuid(),
                    'state_id' => $state->id,
                    'bracket_minimum' => str_replace(['$', ','], '', $rate[1]),
                    'rate_percentage' => str_replace('%', '', $rate[0]),
                    'note' => $i===1 ? 'single' : 'joint',
                    'tax_type' => 1,
                    'tax_category' => $i===1 ? 1 : 2,
                    'country_id' => $country->id,
                    'implementation_date' => '2016-01-01'
                ];
                $taxRates[$i===1 ? 'single' : 'joint'][$state->id][] = $taxRate;
            }
        }

        /*
         * Our data does not have a minimum amount, lets generate those too shall we.
         */
        foreach ($taxRates['single'] as $stateId => $stateTax) {
            foreach ($stateTax as $i => $tax) {
                if (isset($taxRates['single'][$stateId][$i+1])) {
                    $taxRates['single'][$stateId][$i]['bracket_maximum'] = $taxRates['single'][$stateId][$i+1]['bracket_minimum'] -1;
                } else {
                    $taxRates['single'][$stateId][$i]['bracket_maximum'] = null;
                }
            }
        }

        foreach ($taxRates['joint'] as $stateId => $stateTax) {
            foreach ($stateTax as $i => $tax) {
                if (isset($taxRates['joint'][$stateId][$i+1])) {
                    $taxRates['joint'][$stateId][$i]['bracket_maximum'] = $taxRates['joint'][$stateId][$i+1]['bracket_minimum'] -1;
                } else {
                    $taxRates['joint'][$stateId][$i]['bracket_maximum'] = null;
                }
            }
        }

        /**
         * Save to database
         */
        foreach ($taxRates['single'] as $stateId => $stateTaxes) {
            DB::table('tax_rates')->insert($stateTaxes);
        }

        foreach ($taxRates['joint'] as $stateId => $stateTaxes) {
            DB::table('tax_rates')->insert($stateTaxes);
        }
    }

    private function data()
    {
        return 'Alabama,2.00% > $0,2.00% > $0
                Alabama,4.00% > $500,"4.00% > $1,000"
                Alabama,"5.00% > $3,000","5.00% > $6,000"
                Alaska,none,none
                Arizona,2.59% > $0,2.59% > $0
                Arizona,"2.88% > $10,000","2.88% > $20,000"
                Arizona,"3.36% > $25,000","3.36% > $50,000"
                Arizona,"4.24% > $50,000","4.24% > $100,000"
                Arizona,"4.54% > $150,000","4.54% > $300,000"
                Arkansas,0.90% > $0,0.90% > $0
                Arkansas,"2.50% > $4,299","2.50% > $4,299"
                Arkansas,"3.50% > $8,399","3.50% > $8,399"
                Arkansas,"4.50% > $12,599","4.50% > $12,599"
                Arkansas,"6.00% > $20,999","6.00% > $20,999"
                Arkansas,"6.90% > $35,099","6.90% > $35,099"
                California,1.00% > $0,1.00% > $0
                California,"2.00% > $7,850","2.00% > $15,700"
                California,"4.00% > $18,610","4.00% > $37,220"
                California,"6.00% > $29,372","6.00% > $58,744"
                California,"8.00% > $40,773","8.00% > $81,546"
                California,"9.30% > $51,530","9.30% > $103,060"
                California,"10.30% > $263,222","10.30% > $526,444"
                California,"11.30% > $315,866","11.30% > $631,732"
                California,"12.30% > $526,443","12.30% > $1,000,000"
                California,"13.30% > $1,000,000","13.30% > $1,052,886"
                Colorado,4.63% > $0,4.63% > $0
                Connecticut,3.00% > $0,3.00% > $0
                Connecticut,"5.00% > $10,000","5.00% > $20,000"
                Connecticut,"5.50% > $50,000","5.50% > $100,000"
                Connecticut,"6.00% > $100,000","6.00% > $200,000"
                Connecticut,"6.50% > $200,000","6.50% > $400,000"
                Connecticut,"6.90% > $250,000","6.90% > $500,000"
                Connecticut,"6.99% > $500,000","6.99% > $1,000,000"
                Delaware,"2.20% > $2,000","2.20% > $2,000"
                Delaware,"3.90% > $5,000","3.90% > $5,000"
                Delaware,"4.80% > $10,000","4.80% > $10,000"
                Delaware,"5.20% > $20,000","5.20% > $20,000"
                Delaware,"5.55% > $25,000","5.55% > $25,000"
                Delaware,"6.60% > $60,000","6.60% > $60,000"
                Florida,none,none
                Georgia,1.00% > $0,1.00% > $0
                Georgia,2.00% > $750,"2.00% > $1,000"
                Georgia,"3.00% > $2,250","3.00% > $3,000"
                Georgia,"4.00% > $3,750","4.00% > $5,000"
                Georgia,"5.00% > $5,250","5.00% > $7,000"
                Georgia,"6.00% > $7,000","6.00% > $10,000"
                Hawaii,1.40% > $0,1.40% > $0
                Hawaii,"3.20% > $2,400","3.20% > $4,800"
                Hawaii,"5.50% > $4,800","5.50% > $9,600"
                Hawaii,"6.40% > $9,600","6.40% > $19,200"
                Hawaii,"6.80% > $14,400","6.80% > $28,800"
                Hawaii,"7.20% > $19,200","7.20% > $38,400"
                Hawaii,"7.60% > $24,000","7.60% > $48,000"
                Hawaii,"7.90% > $36,000","7.90% > $72,000"
                Hawaii,"8.25% > $48,000","8.25% > $96,000"
                Idaho,1.60% > $0,1.60% > $0
                Idaho,"3.60% > $1,452","3.60% > $2,904"
                Idaho,"4.10% > $2,940","4.10% > $5,808"
                Idaho,"5.10% > $4,356","5.10% > $8,712"
                Idaho,"6.10% > $5,808","6.10% > $11,616"
                Idaho,"7.10% > $7,260","7.10% > $14,520"
                Idaho,"7.40% > $10,890","7.40% > $21,780"
                Illinois,4.95% > $0,4.95% > $0
                Indiana,3.3% > $0,3.3% > $0
                Iowa,0.36% > $0,0.36% > $0
                Iowa,"0.72% > $1,554","0.72% > $1,554"
                Iowa,"2.43% > $3,108","2.43% > $3,108"
                Iowa,"4.50% > $6,216","4.50% > $6,216"
                Iowa,"6.12% > $13,896","6.12% > $13,896"
                Iowa,"6.48% > $23,310","6.48% > $23,310"
                Iowa,"6.80% > $31,080","6.80% > $31,080"
                Iowa,"7.92% > $46,620","7.92% > $46,620"
                Iowa,"8.98% > $69,930","8.98% > $69,930"
                Kansas,2.70% > $0,2.70% > $0
                Kansas,"4.60% > $15,000","4.60% > $30,000"
                Kentucky,2.00% > $0,2.00% > $0
                Kentucky,"3.00% > $3,000","3.00% > $3,000"
                Kentucky,"4.00% > $4,000","4.00% > $4,000"
                Kentucky,"5.00% > $5,000","5.00% > $5,000"
                Kentucky,"5.80% > $8,000","5.80% > $8,000"
                Kentucky,"6.00% > $75,000","6.00% > $75,000"
                Louisiana,2.00% > $0,2.00% > $0
                Louisiana,"4.00% > $12,500","4.00% > $25,000"
                Louisiana,"6.00% > $50,000","6.00% > $100,000"
                Maine,5.80% > $0,5.80% > $0
                Maine,"6.75% > $21,049","6.75% > $42,099"
                Maine,"7.15% > $37,499","7.15% > $74,999"
                Maryland,2.00% > $0,2.00% > $0
                Maryland,"3.00% > $1,000","3.00% > $1,000"
                Maryland,"4.00% > $2,000","4.00% > $2,000"
                Maryland,"4.75% > $3,000","4.75% > $3,000"
                Maryland,"5.00% > $100,000","5.00% > $150,000"
                Maryland,"5.25% > $125,000","5.25% > $175,000"
                Maryland,"5.50% > $150,000","5.50% > $225,000"
                Maryland,"5.75% > $250,000","5.75% > $300,000"
                Massachusetts,5.10% > $0,5.10% > $0
                Michigan,4.25% > $0,4.25% > $0
                Minnesota,5.35% > $0,5.35% > $0
                Minnesota,"7.05% > $25,180","7.05% > $36,820"
                Minnesota,"7.85% > $82,740","7.85% > $146,270"
                Minnesota,"9.85% > $155,650","9.85% > $259,420"
                Mississippi,3.00% > $0,3.00% > $0
                Mississippi,"4.00% > $5,000","4.00% > $5,000"
                Mississippi,"5.00% > $10,000","5.00% > $10,000"
                Missouri,1.50% > $0,1.50% > $0
                Missouri,"2.00% > $1,000","2.00% > $1,000"
                Missouri,"2.50% > $2,000","2.50% > $2,000"
                Missouri,"3.00% > $3,000","3.00% > $3,000"
                Missouri,"3.50% > $4,000","3.50% > $4,000"
                Missouri,"4.00% > $5,000","4.00% > $5,000"
                Missouri,"4.50% > $6,000","4.50% > $6,000"
                Missouri,"5.00% > $7,000","5.00% > $7,000"
                Missouri,"5.50% > $8,000","5.50% > $8,000"
                Missouri,"6.00% > $9,000","6.00% > $9,000"
                Montana,1.00% > $0,1.00% > $0
                Montana,"2.00% > $2,900","2.00% > $2,900"
                Montana,"3.00% > $5,100","3.00% > $5,100"
                Montana,"4.00% > $7,800","4.00% > $7,800"
                Montana,"5.00% > $10,500","5.00% > $10,500"
                Montana,"6.00% > $13,500","6.00% > $13,500"
                Montana,"6.90% > $17,400","6.90% > $17,400"
                Nebraska,2.46% > $0,2.46% > $0
                Nebraska,"3.51% > $3,060","3.51% > $6,120"
                Nebraska,"5.01% > $18,370","5.01% > $36,730"
                Nebraska,"6.84% > $29,590","6.84% > $59,180"
                Nevada,none,none
                New Hampshire,5.00% > $0,5.00% > $0
                New Jersey,1.40% > $0,1.40% > $0
                New Jersey,"1.75% > $20,000","1.75% > $20,000"
                New Jersey,"3.50% > $35,000","2.45% > $50,000"
                New Jersey,"5.53% > $40,000","3.50% > $70,000"
                New Jersey,"6.37% > $75,000","5.53% > $80,000"
                New Jersey,"8.97% > $500,000","6.37% > $150,000"
                New Jersey,,"8.97% > $500,000"
                New Mexico,1.70% > $0,1.70% > $0
                New Mexico,"3.20% > $5,500","3.20% > $8,000"
                New Mexico,"4.70% > $11,000","4.70% > $16,000"
                New Mexico,"4.90% > $16,000","4.90% > $24,000"
                New York,4.00% > $0,4.00% > $0
                New York,"4.50% > $8,450","4.50% > $17,050"
                New York,"5.25% > $11,650","5.25% > $23,450"
                New York,"5.90% > $13,850","5.90% > $27,750"
                New York,"6.45% > $21,300","6.45% > $42,750"
                New York,"6.65% > $80,150","6.65% > $160,500"
                New York,"6.85% > $214,000","6.85% > $321,050"
                New York,"8.82% > $1,070,350","8.82% > $2,140,900"
                North Carolina,5.75% > $0,5.75% > $0
                North Dakota,1.10% > $0,1.10% > $0
                North Dakota,"2.04% > $37,450","2.04% > $62,600"
                North Dakota,"2.27% > $90,750","2.27% > $151,200"
                North Dakota,"2.64% > $189,300","2.64% > $230,450"
                North Dakota,"2.90% > $411,500","2.90% > $411,500"
                Ohio,0.50% > $0,0.50% > $0
                Ohio,"0.99% > $5,200","0.99% > $5,200"
                Ohio,"1.98% > $10,400","1.98% > $10,400"
                Ohio,"2.48% > $15,650","2.48% > $15,650"
                Ohio,"2.97% > $20,900","2.97% > $20,900"
                Ohio,"3.47% > $41,700","3.47% > $41,700"
                Ohio,"3.96% > $83,350","3.96% > $83,350"
                Ohio,"4.60% > $104,250","4.60% > $104,250"
                Ohio,"5.00% > $208,500","5.00% > $208,500"
                Oklahoma,0.50% > $0,0.50% > $0
                Oklahoma,"1.00% > $1,000","1.00% > $2,000"
                Oklahoma,"2.00% > $2,500","2.00% > $5,000"
                Oklahoma,"3.00% > $3,750","3.00% > $7,500"
                Oklahoma,"4.00% > $4,900","4.00% > $9,800"
                Oklahoma,"5.00% > $7,200","5.00% > $12,200"
                Oregon,5.00% > $0,5.00% > $0
                Oregon,"7.00% > $3,350","7.00% > $6,500"
                Oregon,"9.00% > $8,400","9.00% > $16,300"
                Oregon,"9.90% > $125,000","9.90% > $250,000"
                Pennsylvania,3.07% > $0,3.07% > $0
                Rhode Island,3.75% > $0,3.75% > $0
                Rhode Island,"4.75% > $60,850","4.75% > $60,850"
                Rhode Island,"5.99% > $138,300","5.99% > $138,300"
                South Carolina,0.00% > $0,0.00% > $0
                South Carolina,"3.00% > $2,920","3.00% > $2,920"
                South Carolina,"4.00% > $5,840","4.00% > $5,840"
                South Carolina,"5.00% > $8,760","5.00% > $8,760"
                South Carolina,"6.00% > $11,680","6.00% > $11,680"
                South Carolina,"7.00% > $14,600","7.00% > $14,600"
                South Dakota,none,none
                Tennessee,6.00% > $0,6.00% > $0
                Texas,none,none
                Utah,5.00% > $0,5.00% > $0
                Vermont,3.55% > $0,3.55% > $0
                Vermont,"6.80% > $39,900","6.80% > $69,900"
                Vermont,"7.80% > $93,400","7.80% > $160,450"
                Vermont,"8.80% > $192,400","8.80% > $240,000"
                Vermont,"8.95% > $415,600","8.95% > $421,900"
                Virginia,2.00% > $0,2.00% > $0
                Virginia,"3.00% > $3,000","3.00% > $3,000"
                Virginia,"5.00% > $5,000","5.00% > $5,000"
                Virginia,"5.75% > $17,000","5.75% > $17,000"
                Washington,none,none
                West Virginia,3.00% > $0,3.00% > $0
                West Virginia,"4.00% > $10,000","4.00% > $10,000"
                West Virginia,"4.50% > $25,000","4.50% > $25,000"
                West Virginia,"6.00% > $40,000","6.00% > $40,000"
                West Virginia,"6.50% > $60,000","6.50% > $60,000"
                Wisconsin,4.00% > $0,4.00% > $0
                Wisconsin,"5.84% > $11,150","5.84% > $14,820"
                Wisconsin,"6.27% > $22,230","6.27% > $29,640"
                Wisconsin,"7.65% > $244,750","7.65% > $326,330"
                Wyoming,none,none
                District of Columbia,4.00% > $0,4.00% > $0
                District of Columbia,"6.00% > $10,000","6.00% > $10,000"
                District of Columbia,"6.50% > $40,000","6.50% > $40,000"
                District of Columbia,"8.50% > $60,000","8.50% > $60,000"
                District of Columbia,"8.75% > $350,000","8.75% > $350,000"';
    }
}
