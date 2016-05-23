@extends('web::layouts.grids.12')

@section('title', trans('web::seat.home'))
@section('page_header', trans('web::seat.home'))
@section('page_description', trans('web::seat.home_page'))

@section('full')

  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-server"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.online_layers') }}</span>
          <span class="info-box-number">
            {{ $server_status['onlinePlayers'] or trans('web::seat.unknown') }}
          </span>
          <span class="text-muted">
            {{ trans('web::seat.last_update') }}: {{ human_diff($server_status['created_at']) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-key"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.owned_api_keys') }}</span>
          <span class="info-box-number">
            {{ count(auth()->user()->keys) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->

  </div>

@stop
