<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Propaganistas\LaravelIntl\Facades\Currency as Currency;
use Propaganistas\LaravelIntl\Facades\Number as Number;

class TaxRateCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'country' => $this->country->country_name,
            'state' => $this->state ? $this->state->state_name : 'N/A',
            'county' => $this->county ? $this->county->county_name : 'N/A',
            'income_bracket' => $this->getIncomeBracket(),
            'rate' => Number::percent($this->rate_percentage / 100),
            'note' => $this->getNote()
        ];
    }

    private function getIncomeBracket()
    {
        if ($this->bracket_maximum != null) {
            return Currency::format($this->bracket_minimum, $this->country->currency_code) . ' - ' . Currency::format($this->bracket_maximum, $this->country->currency_code);
        } else {
            return Currency::format($this->bracket_minimum, $this->country->currency_code) . ' and above';
        }
    }

    private function getNote()
    {
        switch ($this->note) {
            case 'single':
                return 'Single Filer';

            case 'joint':
                return 'Married Filing Jointly';

            default:
                return $this->note;
        }
    }
}
