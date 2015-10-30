@extends('web::character.layouts.view', ['viewname' => 'notifications'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Notifications')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Notifications')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Heading</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Sent</th>
          <th>From</th>
          <th>Info</th>
        </tr>

        @foreach($notifications as $notification)

          <tr>
            <td>
              <span data-toggle="tooltip" title="" data-original-title="{{ $notification->sentDate }}">
                {{ human_diff($notification->sentDate) }}
              </span>
            </td>
            <td>
              {!! img('auto', $notification->senderID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $notification->senderName }}
            </td>
            <td>
              <i class="fa fa-comment" data-toggle="popover" data-placement="top" title="" data-html="true"
                 data-trigger="hover" data-content="{{ $notification->text }}"></i>
              {{ $notification->desc }}
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">Footer</div>
  </div>

@stop
