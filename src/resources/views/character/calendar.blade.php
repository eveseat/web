@extends('web::character.layouts.view', ['viewname' => 'calendar'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.calendar'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.calendar'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.calendar_events') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.owner') }}</th>
          <th>{{ trans('web::seat.description') }}</th>
          <th>{{ trans('web::seat.status') }}</th>
        </tr>

        @foreach($events as $event)

          <tr>
            <td>
              <span data-toggle="tooltip" title="" data-original-title="{{ $event->eventDate }}">
                {{ human_diff($event->eventDate) }}
              </span>
            </td>
            <td>
              {!! img('auto', $event->ownerID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $event->ownerName }}
            </td>
            <td>
              <i class="fa fa-comment" data-toggle="popover" data-placement="top" title="" data-html="true"
                 data-trigger="hover" data-content="{{ clean_ccp_html($event->eventText) }}"></i>
              {{ $event->eventTitle }}
            </td>
            <td>{{ $event->response }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
