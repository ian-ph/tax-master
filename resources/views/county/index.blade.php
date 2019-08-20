@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-8"><h2>Counties</h2></div>
    <div class="col-sm-4 text-right"><a href="{{ route('county.add') }}" class="btn btn-success">Add County</a></div>
</div>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Counties</li>
    </ol>
</nav>


<div class="card">
    <div class="card-body p-0">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="table-loading text-center d-none p-3">
            Loading..
        </div>
        <div class="table-empty d-none p-3">
            <p>There's nothing to display.</p>
            <p>Begin by <a href="#">creating</a> a new record.</p>
        </div>
        <div class="table-data d-none" data-edit_route="{{ route('county.edit', '') }}" data-delete_route="{{ route('county.delete', '') }}">

        </div>
    </div>

    <div class="card-footer table-footer d-none">
        <div class="row">
            <div class="col-6">
                <span class="data-message small"></span>
            </div>
            <div class="col-6 ">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end mb-0">
                        <li class="page-item"><a class="page-link data-first-link data-paginator" href="">First</a></li>
                        <li class="page-item"><a class="page-link data-previous-link data-paginator" href="">Previous Page</a></li>
                        <li class="page-item"><a class="page-link data-next-link data-paginator" href="#">Next Page</a></li>
                        <li class="page-item"><a class="page-link data-last-link data-paginator" href="">Last</a></li>
                    </ul>
                </nav>
            </div>

        </div>


    </div>
</div>
@endsection

@section('client_scripts')
<script>
    $('document').ready(function(){
        formHelper.getIndex('{{ route('api.county.index') }}');
    });
</script>
@endsection
