@extends('web::character.layouts.view', ['viewname' => 'notifications'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.notifications'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.notifications'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.notifications') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.notifications']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_notifications') }}"></i>
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
          <th>{{ trans('web::seat.from') }}</th>
          <th>{{ trans('web::seat.info') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($notifications as $notification)

          <tr>
            <td data-order="{{ $notification->timestamp }}">
              <span data-toggle="tooltip" title="" data-original-title="{{ $notification->timestamp }}">
                {{ human_diff($notification->timestamp) }}
              </span>
            </td>
            <td>
              {!! img('auto', $notification->sender_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              @if(in_array($notification->sender_type, ['character', 'corporation', 'alliance', 'faction']))
                <span class="id-to-name"
                      data-id="{{ $notification->sender_id }}">{{ trans('web::seat.unknown') }}</span>
              @else
              System
              @endif
            </td>
            <td>
              <i class="fa fa-comment" data-toggle="popover" data-placement="top" title="" data-html="true"
                 data-trigger="hover" data-content="{{ clean_ccp_html($notification->text) }}"></i>
              {{ preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $notification->type) }}
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
