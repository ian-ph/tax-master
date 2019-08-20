@extends('layouts.app')

@section('content')
<h2>Edit Country</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('country.index') }}">Countries</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>

<form action="{{ route('api.country.update', $country->uuid) }}" method="post" class="data-form" data-patch="true">
    <div class="card">
        <div class="card-body">
            @include('elements.basic-alerts')
            <div class="form-group">
                <label for="country_name">Country Name</label>
                <input name="country_name" type="text" class="form-control" placeholder="The common name of the country" value="{{ old('country_name', $country->country_name) }}">
            </div>
            <div class="form-group">
                <label for="country_code">Country Code (ISO 2)</label>
                <input name="country_code" type="text" class="form-control" placeholder="Two letter country code. Example: US, GB, KR" value="{{ old('country_name', $country->country_code) }}">
            </div>
        </div>

        @include('elements.form-footer')
    </div>
</form>
@endsection

@section('client_scripts')

@endsection
