@extends('web::character.layouts.view', ['viewname' => 'calendar', 'breadcrumb' => trans('web::seat.calendar')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.calendar'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.calendar_events') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.calendar']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_calendar') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      {{ $dataTable->table() }}

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush