@extends('web::layouts.app-mini')

@section('title', trans('web::seat.sign_in'))

@section('content')

    <div class="login-logo">
        S<b>e</b>AT | {{ trans('web::seat.sso_activation') }}
    </div>

    <hr>

    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('web::seat.sso_confirmation') }}</p>

        <form action="{{ route('auth.eve.confirmation.post') }}" class="form-horizontal" method="post">
            {{ csrf_field() }}

            <div class="box-body">
                <div class="form-group has-feedback">
                    <input type="text" name="name" class="form-control" value="{{ session('eve_sso')->name }}"
                           placeholder="{{ trans('web::seat.username') }}" disabled="disabled">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="{{ trans('web::seat.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{ trans('web::seat.sign_in') }}</button>
            </div>
            <!-- /.box-footer -->

        </form>

    </div>
    <!-- /.login-box-body -->

@stop
