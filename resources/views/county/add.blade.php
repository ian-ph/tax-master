@extends('layouts.app')

@section('content')
<h2>Add County</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('county.index') }}">Counties</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add County</li>
    </ol>
</nav>

<form action="{{ route('api.county.store') }}" method="post" class="data-form" data-patch="false">
    <div class="card">
        <div class="card-body">
            @include('elements.basic-alerts')
            <div class="form-group">
                <label for="country_code">Choose the country where this county belongs.</label>
                <select name="country_code" id="" class="form-control toggle_state">
                    <option value="">Choose a country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country_code }}">{{ $country->country_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="state_code">Choose the state where this county belongs.</label>
                <select name="state_code" id="" class="form-control dynamic_state">
                    <option value="">Choose a state</option>
                </select>
            </div>

            <div class="form-group">
                <label for="county_name">County Name</label>
                <input name="county_name" type="text" class="form-control" placeholder="The common name of the county">
            </div>
            <div class="form-group">
                <label for="county_code">County Code </label>
                <input name="county_code" type="text" class="form-control" placeholder="County code. Example: US-AL, US-AK">
            </div>
        </div>

        @include('elements.form-footer')
    </div>
</form>
@endsection
