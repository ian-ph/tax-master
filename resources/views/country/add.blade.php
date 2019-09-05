@extends('layouts.app')

@section('content')
<h2>Add Country</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('country.index') }}">Countries</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Country</li>
    </ol>
</nav>

<form action="{{ route('api.country.store') }}" method="post" class="data-form" data-patch="false">
    <div class="card">
        <div class="card-body">
            @include('elements.basic-alerts')
            <div class="form-group">
                <label for="country_name">Country Name</label>
                <input name="country_name" type="text" class="form-control" placeholder="The common name of the country">
            </div>
            <div class="form-group">
                <label for="country_code">Country Code (ISO 2)</label>
                <input name="country_code" type="text" class="form-control" placeholder="Two letter country code. Example: US, GB, KR">
            </div>
            <div class="form-group">
                <label for="currency_code">Currency Code </label>
                <input name="currency_code" type="text" class="form-control" placeholder="Currency code. Example: USD, PHP, GBP">
            </div>
            <div class="form-group">
                <label for="computation_type">Tax Computation Type</label>
                <select name="computation_type" id="" class="form-control">
                    <option value="1">Normal</option>
                    <option value="2">Chunked Bracket</option>
                </select>
                <p>Normal means the tax is computed using one appropriate bracket, while Chunked Bracket means the tax is computed using "all" applicable brackets (<a target="_blank" href="https://www.nerdwallet.com/assets/blog/wp-content/uploads/2019/06/TAX_2019_federal-income-tax-brackets_example-1_32000.png">Example</a>)</p>
            </div>
        </div>

        @include('elements.form-footer')
    </div>
</form>
@endsection

@section('client_scripts')

@endsection
