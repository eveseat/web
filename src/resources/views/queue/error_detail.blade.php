@extends('web::layouts.grids.12')

@section('title', trans('web::queue.job_error_detail'))
@section('page_header', trans('web::queue.job_error_detail'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::queue.full_job_error') }}</h3>
    </div>
    <div class="panel-body">

      <span class="text-muted">
        {{ trans('web::queue.error_details_desc') }}
      </span>

      <hr>

      <dl class="dl-horizontal">
        <dt>{{ trans('web::queue.job_id') }}</dt>
        <dd>{{ $job->job_id }}</dd>
        <dt>{{ trans('web::queue.api') }}</dt>
        <dd>{{ $job->api }}</dd>
        <dt>{{ trans('web::queue.scope') }}</dt>
        <dd>{{ $job->scope }}</dd>
        <dt>{{ trans('web::queue.status') }}</dt>
        <dd>{{ $job->status }}</dd>
      </dl>

      <pre>{{ $job->output }}</pre>

    </div>
  </div>

@stop
