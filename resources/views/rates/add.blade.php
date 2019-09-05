@extends('layouts.app')

@section('content')
<h2>Add Tax Rate</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('rates.index') }}">Tax Rates</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Tax Rate</li>
    </ol>
</nav>

<form action="{{ route('api.tax-rate.store') }}" method="post" class="data-form" data-patch="false">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>Tax Scope</h3>
                    <p>This will detemine how the tax will be implemented. Leaving states and county as blank means the tax will be country wide. Leaving county as black, the tax rate will be state wide. You can add multiple tax rates on the same country with multiple levels and the application will compute it accordingly.</p>
                    @include('elements.basic-alerts')
                    <div class="form-group">
                        <label for="country_code">Country</label>
                        <select name="country_code" id="" class="form-control toggle_state">
                            <option value="">Choose a country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->country_code }}">{{ $country->country_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="state_code">State</label>
                        <select name="state_code" id="" class="form-control dynamic_state toggle_county">
                            <option value="">Choose a state</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="county_code">County</label>
                        <select name="county_code" id="" class="form-control dynamic_county">
                            <option value="">Choose a county</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="implementation_date">Implementation Date</label>
                        <input name="implementation_date" type="date" class="form-control">
                    </div>


                    <div class="tax-amount-form">
                        <h3>Tax Amount</h3>
                        <p>You can use percentage, a fixed amount, or both of them. Some tax are computed based on the total taxes rather that the income amount, you can choose the type accordingly below.</p>
                        <div class="form-group">
                            <label for="rate_fixed">Fixed amount rate</label>
                            <input name="rate_fixed" type="text" class="form-control trigger-summarize-tax-value" placeholder="E.g. 20, 15.55, 10.3">
                        </div>
                        <div class="form-group">
                            <label for="rate_percentage">Percentage amount rate</label>
                            <input name="rate_percentage" type="text" class="form-control trigger-summarize-tax-value" placeholder="E.g. 20, 1.99, 3.5">
                        </div>
                        <div class="form-group">
                            <label for="tax_type">Tax Type</label>
                            <select name="tax_type" id="" class="form-control">
                                <option value="1">Compute based on income amount</option>
                                <option value="2">Compute based on the total tax from income</option>
                            </select>
                        </div>
                    </div>

                    <div class="tax-bracket-form">
                        <h3>Income Bracket</h3>
                        <p>The tax will only get implemented when the income is within the range of amounts below. You can leave the maximum bracket blank to make a bracket without a ceiling.</p>
                        <div class="form-group">
                            <label for="bracket_minimum">Minimum Bracket</label>
                            <input type="text" name="bracket_minimum" class="form-control trigger-summarize-tax-bracket" placeholder="E.g. 0, 100000, 300.50">
                        </div>
                        <div class="form-group">
                            <label for="bracket_maximum">Maximum Bracket</label>
                            <input type="text" name="bracket_maximum"  class="form-control trigger-summarize-tax-bracket" placeholder="Leave empty for no maximum bracket">
                        </div>
                    </div>

                    <h3>Notes</h3>
                    <div class="form-group">
                        <label for="note">Leave some decriptive note for this tax rate.</label>
                        <input type="text" name="note" class="form-control" placeholder="">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Tax Rate Summary
                </div>
                <div class="card-body">
                    <div class="form-group tax-location">
                        <p class="m-0 text-uppercase">The tax will be implemented on:</p>
                        <h4 class="my-2 text-success value-text">Not Set</h4>
                        <p class="m-0 country-text text-success" style="display:none">Country of <strong>Philippines</strong></p>
                        <p class="m-0 state-text text-success" style="display:none">State of <strong>Manila</strong></p>
                    </div>
                    <hr>
                    <div class="form-group tax-date">
                        <p class="m-0 text-uppercase">On Taxes Filed Starting On:</p>
                        <h4 class="my-2 text-success tax-date-text">Not Set</h4>
                    </div>
                    <hr>
                    <div class="form-group tax-rate">
                        <p class="m-0 text-uppercase">The tax rate will be:</p>
                        <h4 class="my-2 text-success tax-rate-value">Not Set</h4>
                        <p class="tax-rate-type text-info">*based on the total tax collected</p>
                    </div>
                    <hr>
                    <div class="form-group tax-bracket">
                        <p class="m-0 text-uppercase">For the income bracket:</p>
                        <h4 class="my-2 text-success tax-bracket-value">Not Set</h4>
                        <h4></h4>
                    </div>
                    <hr>
                    <div class="form-group tax-note">
                        <p class="m-0 text-uppercase">Notes:</p>
                        <p class="tax-note-value">None</p>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
