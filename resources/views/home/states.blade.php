@extends('layouts.app')

@section('content')

<h1>{{ $state->state_name }} Tax Overview</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('home.dashboard', ['uuid' => $state->country->uuid]) }}">Dashboard</a></li>
        <li class="breadcrumb-item">{{ $state->state_name }} Tax Overview</li>
    </ol>
</nav>


<div class="row">
    <div class="col-md-4">
        <div class="card mb-3 bg-info text-white">
            <div class="card-body">
                <h5 class="text-uppercase">Total Tax Income</h5>
                <p class="h3">{{ LocaleHelper::currency($stateTaxes->sum('total_tax')) }}</p>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <small class="text-uppercase">Income from <br>single</small>
                        {{ $stateTaxes->where('tax_category', 1)->count() ? LocaleHelper::currency($stateTaxes->where('tax_category', 1)->first()->total_tax) : 'none'}}
                    </div>
                    <div class="col-6">
                        <small class="text-uppercase">Income from <br>  married</small>
                        {{ $stateTaxes->where('tax_category', 2)->count() ? LocaleHelper::currency($stateTaxes->where('tax_category', 2)->first()->total_tax) :'none'}}
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3 bg-info text-white">
            <div class="card-body">
                <h5 class="text-uppercase">Average State Tax Rate</h5>

                <small class="text-uppercase">Overall Average</small>
                <h1>
                   {{ $overallAverage }}
                </h1>
                <hr>
                <small class="text-uppercase">State Average Rate</small>
                <h2>{{ $stateTaxRate }}</h2>

                <small class="text-uppercase">Counties Average Rate</small>
                <h2>{{ $countyTaxRate }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Total Tax Collected Per County</div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            @include('elements.table-header', ['route' => 'home.states', 'caption' => 'County Name', 'align' => 'left', 'column_name' => 'county_name'])
                            @include('elements.table-header', ['route' => 'home.states', 'caption' => 'Avg Tax Rate', 'align' => 'right', 'column_name' => 'average_tax_rate'])
                            @include('elements.table-header', ['route' => 'home.states', 'caption' => 'Avg Tax Income', 'align' => 'right', 'column_name' => 'average_tax'])
                            @include('elements.table-header', ['route' => 'home.states', 'caption' => 'Total Tax', 'align' => 'right', 'column_name' => 'total_tax'])

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxIncome as $countyTaxIncome)
                        <tr>
                            <td>{{ $countyTaxIncome->county_name }}</td>
                            <td class="text-right">{{ $countyTaxIncome->average_tax_rate }}%</td>
                            <td class="text-right">{{ LocaleHelper::currency($countyTaxIncome->average_tax) }}</td>
                            <td class="text-right">{{ LocaleHelper::currency($countyTaxIncome->total_tax) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
