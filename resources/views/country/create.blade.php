@extends('layouts.app')

@section('content')
    <h1>Create Country</h1>
    <p>Create a country. The data is in the dropdown isn't actually in the database. Adding the chosen country will also add other related info like currency.</p>

    <form action="/countries" method="POST">
    	<div class="form-group">
    		<label for="">Choose a country to add</label>
    		<select name="country_code" id="" class="form-control">
    			@foreach($countries as $country)
					<option value="{{ $country['iso_a3'] }}">{{ $country['name']['common'] }}</option>
	    		@endforeach
    		</select>
    	</div>

    	<button type="submit" class="btn btn-primary">Add Country</button>
    </form>
@endsection
