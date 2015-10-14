@extends('web::layouts.grids.12')

@section('title', trans('web::security.security_logs'))
@section('page_header', trans('web::security.security_logs'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::security.security_logs') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::security.security_logs') }}</th>
          <th>{{ trans('web::security.user') }}</th>
          <th>{{ trans('web::security.category') }}</th>
          <th>{{ trans('web::security.message') }}</th>
        </tr>

        @foreach($logs as $log)

          <tr>
            <td>{{ $log->created_at }}</td>
            <td>
              @if($log->user)
                {{ $log->user->name }}
              @endif
            </td>
            <td>{{ $log->category }}</td>
            <td>{{ $log->message }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    @if(count($logs) == 50)
      <div class="panel-footer">
        {!! $logs->render() !!}
      </div>
    @endif
  </div>
@stop
