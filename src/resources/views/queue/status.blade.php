@extends('web::layouts.grids.12')

@section('title', trans('web::queue.queue_manage'))
@section('page_header', trans('web::queue.queue_manage'))

@section('full')

  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-check-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::queue.total_jobs') }}</span>
          <span class="info-box-number">{{ $totals['total_jobs'] }}</span>
          <a href="{{ route('queue.history') }}" class="btn btn-info btn-sm pull-right">
            {{ trans('web::queue.history') }}
          </a>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-truck"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::queue.queued_jobs') }}</span>
          <span class="info-box-number">{{ $totals['queued_jobs'] }}</span>

          @if($totals['total_jobs'] > 0)
            {{
              number_format(((($totals['total_jobs'] - $totals['queued_jobs']) / $totals['total_jobs']) *100),2)
            }}% {{ trans('web::queue.complete') }}
          @endif
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-exchange"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::queue.working_jobs') }}</span>
          <span class="info-box-number">{{ $totals['working_jobs'] }}</span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-exclamation"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::queue.error_jobs') }}</span>
          <span class="info-box-number">{{ $totals['error_jobs'] }}</span>
          <a href="{{ route('queue.errors') }}" class="btn btn-danger btn-sm pull-right">
            {{ trans_choice('web::general.view', 1) }}
          </a>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
  </div>

  <div class="row">

    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::queue.submit_jobs') }}</h3>
        </div>
        <div class="panel-body">

          <a href="{{ route('queue.command.run', ['command_name' => 'eve:queue-keys']) }}"
             class="btn btn-primary btn-block">
            php artisan eve:queue-keys
          </a>
          <a href="{{ route('queue.command.run', ['command_name' => 'eve:update-api-call-list']) }}"
             class="btn btn-primary btn-block">
            php artisan eve:update-api-call-list
          </a>
          <a href="{{ route('queue.command.run', ['command_name' => 'eve:update-eve']) }}"
             class="btn btn-primary btn-block">
            php artisan eve:update-eve
          </a>
          <a href="{{ route('queue.command.run', ['command_name' => 'eve:update-map']) }}"
             class="btn btn-primary btn-block">
            php artisan eve:update-map
          </a>
          <a href="{{ route('queue.command.run', ['command_name' => 'eve:update-server-status']) }}"
             class="btn btn-primary btn-block">
            php artisan eve:update-server-status
          </a>

        </div>
        <div class="panel-footer">
          {{ trans('web::queue.job_submit_desc') }}
        </div>
      </div>
    </div>

    <div class="col-md-8">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="fa fa-exchange"></i>
            {{ trans('web::queue.working_jobs') }}
          </h3>
        </div>
        <div class="panel-body">

          @if(count($working) > 0)

            <table class="table table-condensed table-hover">
              <tbody>
              <tr>
                <th>{{ trans('web::queue.created') }}</th>
                <th>{{ trans('web::queue.updated') }}</th>
                <th>{{ trans('web::queue.owner_id') }}</th>
                <th>{{ trans('web::queue.api') }}</th>
                <th>{{ trans('web::queue.scope') }}</th>
                <th>{{ trans('web::queue.output') }}</th>
                <th>{{ trans('web::queue.status') }}</th>
              </tr>

              @foreach($working as $job)

                <tr>
                  <td>
                    <span data-toggle="tooltip" title="" data-original-title="{{ $job->created_at }}">
                      {{ human_diff($job->created_at) }}
                    </span>
                  </td>
                  <td>
                    <span data-toggle="tooltip" title="" data-original-title="{{ $job->updated_at }}">
                      {{ human_diff($job->updated_at) }}
                    </span>
                  </td>
                  <td>{{ $job->owner_id }}</td>
                  <td>{{ $job->api }}</td>
                  <td>{{ $job->scope }}</td>
                  <td>{{ $job->output }}</td>
                  <td>{{ $job->status }}</td>
                </tr>

              @endforeach

            @else

              <span class="text-muted">
                {{ trans('web::queue.no_working') }}
              </span>

            @endif

            </tbody>
          </table>

        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="fa fa-truck"></i>
            {{ trans('web::queue.queued_jobs') }}
          </h3>
        </div>
        <div class="panel-body">

          @if(count($queued) > 0)

            <table class="table table-condensed table-hover">
              <tbody>
              <tr>
                <th>{{ trans('web::queue.created') }}</th>
                <th>{{ trans('web::queue.owner_id') }}</th>
                <th>{{ trans('web::queue.api') }}</th>
                <th>{{ trans('web::queue.scope') }}</th>
                <th>{{ trans('web::queue.status') }}</th>
              </tr>

              @foreach($queued as $job)

                <tr>
                  <td>
                    <span data-toggle="tooltip" title="" data-original-title="{{ $job->created_at }}">
                      {{ human_diff($job->created_at) }}
                    </span>
                  </td>
                  <td>{{ $job->owner_id }}</td>
                  <td>{{ $job->api }}</td>
                  <td>{{ $job->scope }}</td>
                  <td>{{ $job->status }}</td>
                </tr>

              @endforeach

            @else

              <span class="text-muted">
                {{ trans('web::queue.no_queue') }}
              </span>

            @endif

            </tbody>
          </table>

        </div>
      </div>

    </div>

  </div>

@stop
