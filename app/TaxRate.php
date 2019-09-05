<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;
use Propaganistas\LaravelIntl\Facades\Currency as Currency;

class TaxRate extends Model
{
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

    public function formatTaxRate($fixed, $percentage, $currency = null)
    {
        if ($fixed > 0 && $percentage > 0) {
            return Currency::format($fixed, $currency) . ' + ' . $percentage . '%';
        }

        if ($percentage > 0) {
            return $percentage . '%';
        }

        if ($fixed > 0) {
            return Currency::format($fixed, $currency);
        }

        return 'None';
    }

    public function arrayAverage($array)
    {
        $divisor = 0;
        foreach ($array as $value) {
            if ($value > 0) {
                $divisor ++;
            }
        }

        if ($divisor === 0) {
            return 0;
        }

        return number_format(array_sum($array) / $divisor, 2);
    }

    public function getOverallAverageTaxRate($type, $country_id)
    {
        $taxes = [
            $this->getCountryAverageTaxRate($type, $country_id),
            $this->getStateAverageTaxRate($type, $country_id),
            $this->getCountyAverageTaxRate($type, $country_id)
        ];

        $divisor = 0;
        foreach ($taxes as $tax) {
            if ($tax > 0) {
                $divisor++;
            }
        }

        if ($divisor == 0) {
            return 0;
        }

        $average = array_sum($taxes) / $divisor;
        return number_format($average, 2);
    }

    public function getCountryAverageTaxRate($type, $country_id)
    {
        $result = Cache::rememberForever($country_id . '.country.average.taxrate.' . $type, function () use ($country_id) {
            return TaxRate::select(DB::raw('avg(rate_percentage) as average_tax_rate_percentage, avg(rate_fixed) as average_tax_rate_fixed, country_id'))
                ->whereNull('state_id')
                ->whereNull('county_id')
                ->groupBy('country_id')
                ->where('country_id', $country_id)
                ->first();
        });

        if (empty($result)) {
            return 0;
        }

        return $type == 'percentage' ? number_format($result->average_tax_rate_percentage, 2) : number_format($result->average_tax_rate_fixed, 2);
    }

    public function getStateAverageTaxRate($type, $country_id, $state_id = null)
    {
        $key = implode('-', [$country_id, $state_id]);
        $result = Cache::rememberForever($key . '.state.average.taxrate.' . $type, function () use ($country_id, $state_id) {
            $taxRate = TaxRate::select(DB::raw('avg(rate_percentage) as average_tax_rate_percentage, avg(rate_fixed) as average_tax_rate_fixed, country_id'))
                ->groupBy('country_id')
                ->whereNull('county_id')
                ->where('country_id', $country_id);

            if (!empty($state_id)) {
                $taxRate->where('state_id', $state_id);
            } else {
                $taxRate->where('state_id', '!=', null);
            }

            return $taxRate->first();
        });

        if (empty($result)) {
            return 0;
        }

        return $type == 'percentage' ? number_format($result->average_tax_rate_percentage, 2) : number_format($result->average_tax_rate_fixed, 2);
    }

    public function getCountyAverageTaxRate($type, $country_id, $state_id = null, $county_id = null)
    {
        $key = implode('-', [$country_id, $state_id, $county_id]);
        $result = Cache::rememberForever($key . '.county.average.taxrate.' . $type, function () use ($country_id, $state_id, $county_id) {
            $taxRate = TaxRate::select(DB::raw('avg(rate_percentage) as average_tax_rate_percentage, avg(rate_fixed) as average_tax_rate_fixed, country_id'))
                ->groupBy('country_id')
                ->where('country_id', $country_id);

            if (!empty($state_id)) {
                $taxRate->where('state_id', $state_id);
            } else {
                $taxRate->where('state_id', '!=', null);
            }

            if (!empty($county_id)) {
                $taxRate->where('county_id', $county_id);
            } else {
                $taxRate->where('county_id', '!=', null);
            }

            return $taxRate->first();
        });

        if (empty($result)) {
            return 0;
        }

        return $type == 'percentage' ? number_format($result->average_tax_rate_percentage, 2) : number_format($result->average_tax_rate_fixed, 2);
    }
}
