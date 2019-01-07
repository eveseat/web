@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'log', 'breadcrumb' => trans_choice('web::seat.log', 1)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.log', 1))

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.roles_change_log') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.issuer') }}</th>
          <th>{{ trans('web::seat.affected') }}</th>
          <th>{{ trans_choice('web::seat.state', 1) }}</th>
          <th>{{ trans_choice('web::seat.role', 1) }}</th>
          <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach($logs as $log)

          <tr>
            <td data-order="{{ $log->changed_at }}">
              {{ human_diff($log->changed_at) }}
            </td>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $log->issuer_id]) }}">
                {!! img('character', $log->issuer_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span class="id-to-name" data-id="{{ $log->issuer_id }}">{{ trans('web::seat.unknown') }}</span>
              </a>
            </td>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $log->character_id]) }}">
                {!! img('character', $log->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span class="id-to-name" data-id="{{ $log->character_id }}">{{ trans('web::seat.unknown') }}</span>
              </a>
            </td>
            <td>
              {{ $log->state }}
            </td>
            <td>
              {{ $log->role }}
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
