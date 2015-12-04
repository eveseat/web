@extends('web::layouts.grids.12')

@section('title', 'Home')
@section('page_header', 'Home')
@section('page_description', 'The home page')

@section('full')

  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-server"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Online Players</span>
          <span class="info-box-number">
            {{ $server_status['onlinePlayers'] or 'Unknown' }}
          </span>
          <span class="text-muted">
            Last Update: {{ human_diff($server_status['created_at']) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-key"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Owned API Keys</span>
          <span class="info-box-number">
            {{ count(auth()->user()->keys) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->

  </div>



@stop

