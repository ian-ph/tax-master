<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TaxRate;
use App\TaxIncomeDetail;
use DB;
use Propaganistas\LaravelIntl\Facades\Currency as Currency;
use Cache;

class TaxIncome extends Model
{
    public function compute()
    {
        $computationType = $this->country->computation_type;

        switch ($computationType) {
            case 2: //Ladder
                $taxRates = $this->getLadderTaxRates();
                break;

            default: //simple
                $taxRates = $this->getSimpleTaxRates();
        }

        $standardTax        = $this->computeTax($taxRates->where('tax_type', 1)->all(), $computationType, $this->amount);
        $taxBasedFromTax    = $this->computeTax($taxRates->where('tax_type', 2)->all(), $computationType, $standardTax);

        return $standardTax + $taxBasedFromTax;
    }

    public function computeTax($taxRates, $computationType, $sourceAmount)
    {
        $totalTaxAmount = 0;


        foreach ($taxRates as $taxRate) {
            $taxAmount      = $this->getTaxAmount($taxRate, $computationType, $sourceAmount);
            $totalTaxAmount = $totalTaxAmount + $taxAmount;

            /*
            $taxIncomeDetail                    = new TaxIncomeDetail;
            $taxIncomeDetail->tax_income_id     = $this->id;
            $taxIncomeDetail->tax_rate_id       = $taxRate->id;
            $taxIncomeDetail->amount            = $taxAmount;
            $taxIncomeDetail->save();
            */
        }

        return $totalTaxAmount;
    }

    private function getSimpleTaxRates()
    {
        $taxRates = TaxRate::where('tax_category', $this->tax_category);
        $taxRates = $this->queryByLocation($taxRates);
        $taxRates = $taxRates
        ->where('bracket_minimum', '<=', $this->amount)
        ->where(function ($underOrNoMaximumAmount) {
            $underOrNoMaximumAmount
            ->where('bracket_maximum', '>=', $this->amount)
            ->orWhere('bracket_maximum', null);
        })
        ->get();

        return $taxRates;
    }

    private function getLadderTaxRates()
    {
        $taxRates = TaxRate::where('tax_category', $this->tax_category);
        $taxRates = $this->queryByLocation($taxRates);
        $taxRates = $taxRates
        ->where(function ($ladderBracket) {
            $ladderBracket
            ->where(function ($normalBracket) {
                $normalBracket
                ->where('bracket_minimum', '<=', $this->amount)
                ->where(function ($maximumLimits) {
                    $maximumLimits->where(function ($withinOrNoMaximumAmount) {
                        $withinOrNoMaximumAmount
                        ->where('bracket_maximum', '>=', $this->amount)
                        ->orWhere('bracket_maximum', null);
                    })
                    ->orWhere(function ($underOrNoMaximumAmount) {
                        $underOrNoMaximumAmount
                        ->where('bracket_maximum', '<=', $this->amount)
                        ->orWhere('bracket_maximum', null);
                    });
                });
            });
        })
        ->get();

        return $taxRates;
    }

    private function queryByLocation($taxRates)
    {
        return $taxRates->where(function ($locationQuery) {
            $locationQuery
            ->where(function ($countryWide) {
                $countryWide
                ->where('country_id', $this->country_id)
                ->whereNull('state_id')
                ->whereNull('county_id');
            })
            ->orWhere(function ($stateWide) {
                $stateWide
                ->where('country_id', $this->country_id)
                ->where('state_id', $this->state_id)
                ->whereNull('county_id');
            })
            ->orWhere(function ($countyWide) {
                $countyWide
                ->where('country_id', $this->country_id)
                ->where('state_id', $this->state_id)
                ->where('county_id', $this->county_id);
            });
        });
    }

    private function getTaxAmount($taxRate, $computationType, $sourceAmount)
    {
        $total = 0;
        //tax from fixed rate
        if (!empty($taxRate->rate_fixed)) {
            $total = $taxRate->rate_fixed;
        }
        //tax from percentage rate
        if (!empty($taxRate->rate_percentage)) {
            if ($computationType == 2) {
                //ladder
                if (empty($taxRate->bracket_maximum) || $sourceAmount < $taxRate->bracket_maximum) {
                    $baseAmount = $sourceAmount - ($taxRate->bracket_minimum - 1);
                } else {
                    $baseAmount = $taxRate->bracket_maximum - ($taxRate->bracket_minimum - 1);
                }
                $total = $total + ($baseAmount * ($taxRate->rate_percentage / 100));
            } else {
                //simple
                $total = $total + ($sourceAmount * ($taxRate->rate_percentage / 100));
            }
        }
        return $total;
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }

    public function county()
    {
        return $this->belongsTo('App\County');
    }

    public function getAverageTaxRateAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getCountryAverageTaxRate()
    {
        return  number_format($this->average_country_tax  / ($this->average_raw_income / 100), 2) . ' %';
    }


    public function summaryByCountry($groupByTaxCategory = false)
    {

        return $this->generateTaxIncomeQuery('country_id', $groupByTaxCategory);
    }

    public function summaryByState($groupByTaxCategory = false)
    {
        return $this->generateTaxIncomeQuery('state_id', $groupByTaxCategory);
    }

    public function summaryByCounty($groupByTaxCategory = false)
    {
        return $this->generateTaxIncomeQuery('county_id', $groupByTaxCategory);
    }

    private function generateTaxIncomeQuery($primaryGroup, $groupByTaxCategory = false) {
        $select = 'sum(taxed_amount) as total_tax, avg(taxed_amount) as average_tax, ' . $primaryGroup;
        $select .= $groupByTaxCategory ? ', tax_category' : '';

        $query = $this->with('country')
            ->with('state')
            ->with('county')
            ->select(DB::raw($select))
            ->groupBy($primaryGroup);

        if($groupByTaxCategory) {
            $query->groupBy('tax_category');
        }

        return $query;
    }

    private function getCurrencyCode()
    {
        $country = '';
        if (!empty($this->country)) {
            $country = $this->country;
        } elseif (!empty($this->state)) {
            $country = $this->state->country;
        } elseif (!empty($this->county)) {
            $country = $this->county->country;
        }

        return Cache::rememberForever($country->id.'.currency_code', function () use ($country) {
            return $country->currency_code;
        });
    }

}
