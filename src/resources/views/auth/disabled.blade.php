@extends('web::layouts.app-mini')

@section('title', trans('web::seat.account_disabled'))

@section('content')

    <div class="login-logo">
        S<b>e</b>AT | {{ trans('web::seat.account_disabled') }}
    </div>

    <hr>

    <div class="login-box-body">
        <p class="login-box-msg">
            This account has been disabled. Please contact
            your <a href="mailto:{{ setting('admin_contact', true) }}">administrator</a> in order to get more information.
        </p>

        <a href="{{ route('home') }}" class="btn btn-primary btn-block">
            {{ trans('web::seat.home') }}
        </a>
    </div>

@stop
