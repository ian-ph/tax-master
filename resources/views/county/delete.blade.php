@extends('layouts.app')

@section('content')
<h2>Delete County</h2>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('county.index') }}">Counties</a></li>
        <li class="breadcrumb-item active" aria-current="page">Delete County</li>
    </ol>
</nav>

<form action="{{ route('api.county.destroy', $county->uuid) }}" method="post" class="data-form-delete">
    <div class="card">
        <div class="card-header">
            Are you sure you want to delete this?
        </div>
        <div class="card-body">
            @foreach($county->toArray() as $column => $value)
                @if(!in_array($column, ['id', 'uuid']))
                    <p><strong class="text-capitalize">{{ str_replace('_', ' ', $column) }}:</strong> {{ $value }}</p>
                @endif
            @endforeach
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-6"><a href="{{ route('county.index') }}" class="btn btn-secondary back-button">Go back</a></div>
                <div class="col-6 text-right"><button type="submit" class="btn btn-danger">Yes, Delete it.</button></div>
            </div>
        </div>
    </div>
</form>
@endsection
