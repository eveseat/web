@extends('web::corporation.layouts.view', ['viewname' => 'mining'])

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')
    <div class="row">
        <div class="col-md-12">
            @include('web::corporation.mining.includes.menu')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @yield('mining_content')
        </div>
    </div>
@stop
