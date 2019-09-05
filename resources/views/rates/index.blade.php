@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-8"><h2>Tax Rates</h2></div>
    <div class="col-sm-4 text-right"><a href="{{ route('rates.add') }}" class="btn btn-success">Add Tax Rate</a></div>
</div>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tax Rates</li>
    </ol>
</nav>

<div class="card mb-3">
    <div class="card-header">
        Filters
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                <select name="country-filter" class="form-control">
                    <option value="">All countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->uuid }}">{{ $country->country_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <select name="state-filter"" class="form-control">
                    <option value="">Any State</option>
                </select>
            </div>
            <div class="col-4">
                <select name="county-filter" class="form-control">
                    <option value="">Any County</option>
                </select>
            </div>
        </div>
    </div>
</div>

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
        <div class="table-data d-none" data-edit_route="{{ route('state.edit', '') }}" data-delete_route="{{ route('state.delete', '') }}">

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

    var resourceRoute = '{{ route('api.tax-rate.index') }}';
    $('document').ready(function(){

        formHelper.getIndex(resourceRoute);

        $('select[name=country-filter]').change(function(){
            formHelper.getIndex(resourceRoute + '?' + buildFilter());
            getStates();
        });

        $('select[name=state-filter]').change(function(){
            formHelper.getIndex(resourceRoute + '?' + buildFilter());
        });
    });

    function buildFilter(){
        country = $('select[name=country-filter]').val();
        state = $('select[name=state-filter]').val();
        county = $('select[name=county-filter]').val();

        return $.param({
            country : country,
            state : state,
            county: county
        });
    }

    function getStates(){

        var select = $('select[name=state-filter]');

        select.find('option').remove();
        select.append('<option value="">Any State</option>');

        var select_value = select.data('value') != undefined && select.data('value') != "" ? select.data('value') : '';

        $.ajax({
            url : '/api/state/list_by_uuid/' + $('select[name=country-filter]').val(),
            headers: {
                "Authorization": "Bearer " + $('meta[name=request-token]').attr('content')
            },
            type: 'get',
            success: function(result){
                console.log(select_value);
                $.each(result, function(){
                    selected = select_value == this.state_code ? 'selected' : '';
                    select.append('<option value="'+ this.uuid +'" '+selected+'>'+ this.state_name +'</option>');
                });
            }
        });
    }
</script>
@endsection
