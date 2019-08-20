@extends('layouts.app')

@section('content')
<h2>Edit State</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('county.index') }}">Counties</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>

<form action="{{ route('api.county.update', $county->uuid) }}" method="post" class="data-form" data-patch="true">
    <div class="card">
        <div class="card-body">
            @include('elements.basic-alerts')
            <div class="form-group">
                <label for="country_code">Choose the country where this state belongs.</label>
                <select name="country_code" id="" class="form-control  toggle_state">
                    <option value="">Choose a country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country_code }}" {{ $country->country_code == old('country_code', $county->country->country_code) ? 'selected' : $county->country->country_code }}>{{ $country->country_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="state_code">Choose the state where this county belongs.</label>
                <select name="state_code" id="" class="form-control dynamic_state" data-value="{{ old('state_code', $county->state->state_code) }}">
                    <option value="">Choose a state</option>
                </select>
            </div>
            <div class="form-group">
                <label for="county_name">County Name</label>
                <input name="county_name" type="text" class="form-control" placeholder="The common name of the county" value="{{ old('county_name', $county->county_name) }}">
            </div>
            <div class="form-group">
                <label for="county_code">County Code</label>
                <input name="county_code" type="text" class="form-control" placeholder="County code. Example: US-AL, US-AK" value="{{ old('county_name', $county->county_code) }}">
            </div>
        </div>

        @include('elements.form-footer')
    </div>
</form>
@endsection
