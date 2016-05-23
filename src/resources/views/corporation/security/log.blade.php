@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'log'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.log', 1))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.log', 1))

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.roles_change_log') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans('web::seat.date') }}</th>
            <th>{{ trans('web::seat.issuer') }}</th>
            <th>{{ trans('web::seat.affected') }}</th>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        @foreach($logs as $log)

          <tr>
            <td data-order="{{ $log->changeTime }}">
              {{ human_diff($log->changeTime) }}
            </td>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $log->issuerID]) }}">
                {!! img('character', $log->issuerID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $log->issuerName }}
              </a>
            </td>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $log->characterID]) }}">
                {!! img('character', $log->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $log->characterName }}
              </a>
            </td>
            <td>
              {{ $log->roleLocationType }}
            </td>
            <td>
              <span class="label label-default" data-toggle="tooltip"
                 title=""
                 data-original-title="{{ implode(" ", array_flatten(json_decode($log->oldRoles, true))) }}">
                {{ count(array_flatten(json_decode($log->oldRoles, true))) }}
              </span>
              <i class="fa fa-long-arrow-right"></i>
              <span class="label label-default" data-toggle="tooltip"
                    title=""
                    data-original-title="{{ implode(" ", array_flatten(json_decode($log->newRoles, true))) }}">
                {{ count(array_flatten(json_decode($log->newRoles, true))) }}
              </span>

            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
