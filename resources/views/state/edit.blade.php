@extends('layouts.app')

@section('content')
<h2>Edit State</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('state.index') }}">State</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>

<form action="{{ route('api.state.update', $state->uuid) }}" method="post" class="data-form" data-patch="true">
    <div class="card">
        <div class="card-body">
            @include('elements.basic-alerts')
            <div class="form-group">
                <label for="country_code">Choose the country where this state belongs.</label>
                <select name="country_code" id="" class="form-control">
                    <option value="">Choose a country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country_code }}" {{ $country->country_code == old('country_code', $state->country->country_code) ? 'selected' : $state->country->country_code }}>{{ $country->country_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="state_name">State Name</label>
                <input name="state_name" type="text" class="form-control" placeholder="The common name of the state" value="{{ old('state_name', $state->state_name) }}">
            </div>
            <div class="form-group">
                <label for="state_code">State Code</label>
                <input name="state_code" type="text" class="form-control" placeholder="State code. Example: US-AL, US-AK" value="{{ old('state_name', $state->state_code) }}">
            </div>
        </div>

        @include('elements.form-footer')
    </div>
</form>
@endsection
