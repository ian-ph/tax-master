@extends('layouts.app')

@section('content')
<h2>Add State</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('state.index') }}">States</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add State</li>
    </ol>
</nav>

<form action="{{ route('api.state.store') }}" method="post" class="data-form" data-patch="false">
    <div class="card">
        <div class="card-body">
            @include('elements.basic-alerts')
            <div class="form-group">
                <label for="country_code">Choose the country where this state belongs.</label>
                <select name="country_code" id="" class="form-control">
                    <option value="">Choose a country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country_code }}">{{ $country->country_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="state_name">State Name</label>
                <input name="state_name" type="text" class="form-control" placeholder="The common name of the state">
            </div>
            <div class="form-group">
                <label for="state_code">State Code </label>
                <input name="state_code" type="text" class="form-control" placeholder="State code. Example: US-AL, US-AK">
            </div>
        </div>

        @include('elements.form-footer')
    </div>
</form>
@endsection
