<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\County;
use App\State;
use App\TaxIncome;
use App\TaxRate;
use DB;
use Propaganistas\LaravelIntl\Facades\Currency as Currency;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * A proxy to simple redirect an empty route to the dashboard
     * @return [type] [description]
     */
    public function index()
    {
        return redirect(route('home.dashboard'));
    }

    /**
     * Show the application dashboard.
     *
     * @param string $uuid The uuid of the country's statistics to be displayed
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request, $uuid = '')
    {
        $countries = Country::all();
        $taxIncome = new TaxIncome;

        if (empty($request->uuid)) {
            return view('home.dashboard', [
                'empty_page'    => true,
                'countries'     => $countries,
            ]);
        }

        $country = $countries->where('uuid', $request->uuid)->first();
        $request->session()->put('current.country.currency', $country->currency_code);


        $countryTaxes = $taxIncome->summaryByCountry(true)
            ->where('country_id', $country->id)
            ->get();


        $taxRate = new TaxRate;

        $overallTaxRate = $taxRate->formatTaxRate(
            $taxRate->getOverallAverageTaxRate('fixed', $country->id),
            $taxRate->getOverallAverageTaxRate('percentage', $country->id),
            $country->currency_code
        );
        $countryTaxRate = $taxRate->formatTaxRate(
            $taxRate->getCountryAverageTaxRate('fixed', $country->id),
            $taxRate->getCountryAverageTaxRate('percentage', $country->id),
            $country->currency_code
        );
        $stateTaxRate = $taxRate->formatTaxRate(
            $taxRate->getStateAverageTaxRate('fixed', $country->id),
            $taxRate->getStateAverageTaxRate('percentage', $country->id),
            $country->currency_code
        );
        $countyTaxRate = $taxRate->formatTaxRate(
            $taxRate->getCountyAverageTaxRate('fixed', $country->id),
            $taxRate->getCountyAverageTaxRate('percentage', $country->id),
            $country->currency_code
        );

        $stateTaxes = $taxIncome->summaryByState()
        ->addSelect('states.state_name', 'states.uuid')
        ->join('states', 'tax_incomes.state_id', '=', 'states.id');

        if (!empty($request->sort)) {
            $sortDirection = !empty($request->sort_direction) && in_array($request->sort_direction, ['asc', 'desc']) ? $request->sort_direction : 'desc';
            $stateTaxes->orderBy($request->sort, $sortDirection);
        } else {
            $stateTaxes->orderBy('total_tax', 'desc');
        }
        $stateTaxes = $stateTaxes->get();

        return view('home.dashboard', [
            'countries'             => $countries,
            'currentCountry'        => $country,
            'empty_page'            => false,
            'countryTaxes'          => $countryTaxes,
            'overallTaxRate'        => $overallTaxRate,
            'countryTaxRate'        => $countryTaxRate,
            'stateTaxRate'          => $stateTaxRate,
            'countyTaxRate'         => $countyTaxRate,
            'stateTaxes'            => $stateTaxes,
        ]);
    }

    /**
     * Show the states statistics page
     *
     * @param string $uuid The uuid of the states's statistics to be displayed
     *
     * @return \Illuminate\Http\Response
     */
    public function states(Request $request, $uuid)
    {
        $state = State::with('country')->where('uuid', $uuid)->first();
        if (empty($state)) {
            abort('404');
        }

        $stateTax = TaxIncome::with('country')
            ->select(DB::raw('sum(taxed_amount) as total_tax, country_id'))
            ->groupBy('country_id')
            ->orderBy('total_tax', 'desc')
            ->where('country_id', $state->country_id)
            ->where('state_id', $state->id)
            ->first();

        $stateTaxByType = TaxIncome::with('country')
            ->select(DB::raw('sum(taxed_amount) as total_tax, country_id, tax_category'))
            ->groupBy('country_id')
            ->groupBy('tax_category')
            ->orderBy('total_tax', 'desc')
            ->where('country_id', $state->country_id)
            ->where('state_id', $state->id)
            ->get();

        $taxIncome = TaxIncome::with('country')
            ->select(DB::raw('sum(taxed_amount) as total_tax, avg(taxed_amount) as average_tax, avg(tax_rates.rate_percentage) as average_tax_rate, counties.uuid, counties.county_name, counties.country_id'))
            ->leftJoin('counties', 'tax_incomes.county_id', '=', 'counties.id')
            ->leftJoin('tax_rates', 'tax_rates.county_id', '=', 'counties.id')
            ->where('counties.country_id', $state->country_id)
            ->where('counties.state_id', $state->id)
            ->groupBy('counties.county_name')
            ->groupBy('counties.uuid')
            ->groupBy('counties.country_id');

        $taxRate = new TaxRate;
        $stateTaxRate = $taxRate->formatTaxRate(
            $taxRate->getStateAverageTaxRate('fixed', $state->country->id),
            $taxRate->getStateAverageTaxRate('percentage', $state->country->id, $state->state_id),
            $state->country->currency_code
        );
        $countyTaxRate = $taxRate->formatTaxRate(
            $taxRate->getCountyAverageTaxRate('fixed', $state->country->id),
            $taxRate->getCountyAverageTaxRate('percentage', $state->country->id, $state->state_id),
            $state->country->currency_code
        );

        $overallAverage = $taxRate->formatTaxRate(
            $taxRate->arrayAverage([$taxRate->getStateAverageTaxRate('fixed', $state->country->id), $taxRate->getCountyAverageTaxRate('fixed', $state->country->id)]),
            $taxRate->arrayAverage([$taxRate->getStateAverageTaxRate('percentage', $state->country->id), $taxRate->getCountyAverageTaxRate('percentage', $state->country->id)]),
            $state->country->currency_code
        );

        if (!empty($request->sort)) {
            $sortDirection = !empty($request->sort_direction) && in_array($request->sort_direction, ['asc', 'desc']) ? $request->sort_direction : 'desc';
            $taxIncome->orderBy($request->sort, $sortDirection);
        } else {
            $taxIncome->orderBy('total_tax', 'desc');
        }
        $taxIncome = $taxIncome->get();



        return view('home.states', [
            'stateTax' => $stateTax,
            'stateTaxRate'          => $stateTaxRate,
            'countyTaxRate'         => $countyTaxRate,
            'overallAverage'        => $overallAverage,
            'stateTaxByType' => $stateTaxByType,
            'state' => $state,
            'taxIncome' => $taxIncome
        ]);
    }
}
