@extends('web::layouts.app-mini')

@section('title', trans('web::seat.requirements'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::seat.requirements') }}
  </div>

  <hr>

  <div class="card">
    <div class="card-body login-box-body">
      <p class="login-box-msg">
        {{ trans('web::seat.requirements_message') }}
      </p>

      <dl class="dl-horizontal">

        @foreach($requirements as $requirement)

          <dt>{{ $requirement['name'] }}</dt>
          <dd>
            @if($requirement['loaded'])
              <span class="text-success">Loaded</span>
            @else
              <span class="text-danger">Missing</span>
            @endif
          </dd>

        @endforeach

      </dl>

    </div>
    <!-- /.login-box-body -->
  </div>

@stop
