@extends('layouts.app')

@section('content')

<h1>Dashboard</h1>
 <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item">Dashboard</li>
</ol>

<div class="form-group dashboard-country-chooser">
<form action="{{ route('home.dashboard') }}" type="get">
        <input type="hidden" name="sort" value="average_tax">
        <input type="hidden" name="sort_direction" value="desc">
        <select name="uuid" id="" class="form-control form-control-lg">
            <option value="">Choose a country to begin..</option>
            @foreach($countries as $country)
                <option value="{{ $country->uuid }}" {{ !empty($currentCountry) && $country->id == $currentCountry->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
            @endforeach
        </select>
    </form>
</div>

@if(!$empty_page)
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3 bg-success text-white">
            <div class="card-body">
                <h5 class="text-uppercase">Total Tax Income</h5>
                <p class="h3">{{ LocaleHelper::currency($countryTaxes->sum('total_tax')) }}</p>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <small class="text-uppercase">Income from single</small>
                        {{ LocaleHelper::currency($countryTaxes->where('tax_category', 1)->first()->total_tax) }}
                    </div>
                    <div class="col-6">
                        <small class="text-uppercase">Income from married</small>
                        {{ LocaleHelper::currency($countryTaxes->where('tax_category', 2)->first()->total_tax) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3 bg-success text-white">
            <div class="card-body">
                <h5 class="text-uppercase">Country Average Tax Rate</h5>
                <p class="h1">{{ $overallTaxRate }}</p>
                <hr>

                <div class="form-group">
                    <small class="text-uppercase">Average Country-wide Tax Rate</small>
                    <p class="h5">{{ $countryTaxRate }}</p>
                </div>
                <div class="form-group">
                    <small class="text-uppercase">Average state-wide Tax Rate</small>
                    <p class="h5">{{ $stateTaxRate }}</p>
                </div>
                <div class="form-group">
                    <small class="text-uppercase">Average county-wide Tax Rate</small>
                    <p class="h5">{{ $countyTaxRate }}</p>
                </div>

            </div>
        </div>

    </div>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Total Tax Collected Per State</div>
                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    @include('elements.table-header', ['route' => 'home.dashboard', 'caption' => 'State Name', 'align' => 'left', 'column_name' => 'state_name'])
                                    @include('elements.table-header', ['route' => 'home.dashboard', 'caption' => 'Average Tax', 'align' => 'right', 'column_name' => 'average_tax'])
                                    @include('elements.table-header', ['route' => 'home.dashboard', 'caption' => 'Total Tax', 'align' => 'right', 'column_name' => 'total_tax'])
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stateTaxes as $stateTax)
                                <tr>
                                    <td>{{ $stateTax->state->state_name }}</td>
                                    <td class="text-right">{{ LocaleHelper::currency($stateTax->average_tax) }}</td>
                                    <td class="text-right">{{ LocaleHelper::currency($stateTax->total_tax) }}</td>
                                    <td class="text-right"><a href="{{ route('home.states', ['uuid' => $stateTax->uuid]) }}" class="">More Info</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
