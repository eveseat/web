@extends('web::layouts.grids.12')

@section('title', trans('web::seat.home'))
@section('page_header', trans('web::seat.home'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

  <div class="row">
    <div class="d-flex">
      <div class="flex-grow-1">
        <h1>{{ trans(collect(config('seat.config.dashboards'))->where('class', setting('dashboard') ?? \Seat\Web\Http\Composers\Dashboards\CharacterDashboard::class)->first()['label']) }}</h1>
      </div>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="dashboardDropDownMenu">Choose a board</button>
        <ul class="dropdown-menu" aria-labelledby="dashboardDropDownMenu">
          @foreach(config('seat.config.dashboards', []) as $board)
            <li>
              <button @class(['dropdown-item', 'active' => $board['class'] == setting('dashboard')]) role="button">{{ trans($board['label']) }}</button>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
  <div class="row">
    @include($dashboard)
  </div>

@endsection