@extends('web::character.layouts.view', ['viewname' => 'calendar'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Calendar')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Calendar')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Calendar Events</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Date</th>
          <th>Owner</th>
          <th>Description</th>
          <th>Status</th>
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
                 data-trigger="hover" data-content="{{ $event->eventText }}"></i>
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
