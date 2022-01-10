@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 1) . ' - ' . $character->name . (isset($breadcrumb) ? ' > ' . $breadcrumb : ''))
@section('page_header', $character->name)

@section('full')

    <div class="row">

        <div class="col-md-3 col-xxl-2">

            @include('web::character.includes.sidecard')

        </div>

        <div class="col-md-9 col-xxl-10">

            @yield('character_content')

        </div>

    </div>

@endsection
