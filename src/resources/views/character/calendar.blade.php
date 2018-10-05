@extends('web::character.layouts.view', ['viewname' => 'calendar'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.calendar'))
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

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.owner') }}</th>
          <th>{{ trans('web::seat.description') }}</th>
          <th>{{ trans('web::seat.status') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($events as $event)

          <tr>
            <td data-order="{{ $event->event_date }}">
              <span data-toggle="tooltip" title="" data-original-title="{{ $event->event_date }}">
                {{ human_diff($event->event_date) }}
              </span>
            </td>
            <td>
              {!! img('auto', $event->detail->owner_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span class="id-to-name" data-id="{{ $event->detail->owner_id }}">{{ trans('web::seat.unknown') }}</span>
            </td>
            <td>
              <i class="fa fa-comment" data-toggle="popover" data-placement="top" title="" data-html="true"
                 data-trigger="hover" data-content="{{ clean_ccp_html($event->detail->text) }}"></i>
              {{ $event->title }}
            </td>
            <td>{{ str_replace('_', ' ', $event->event_response) }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
