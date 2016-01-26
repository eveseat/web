@extends('web::layouts.app-mini')

@section('title', trans('web::seat.php_version'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::seat.php_version') }}
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      {{ trans('web::seat.php_version_message') }}
    </p>

    <dl class="dl-horizontal">

      <dt>{{ trans('web::seat.installed_version') }}</dt>
      <dd><span class="text-danger">{{ phpversion() }}</span></dd>
      <dt>{{ trans('web::seat.min_version') }}</dt>
      <dd>5.5.14</dd>

    </dl>

  </div>
  <!-- /.login-box-body -->

@stop
