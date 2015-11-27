@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'log'])

@section('title', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Logs')
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Logs')

@section('security_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Roles Change Log</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Date</th>
          <th>Issuer</th>
          <th>Affected</th>
          <th>Role Location Type</th>
        </tr>

        @foreach($logs as $log)

          <tr>
            <td>
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
