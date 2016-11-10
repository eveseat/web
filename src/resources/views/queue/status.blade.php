@extends('web::layouts.grids.12')

@section('title', trans('web::seat.queue_manage'))
@section('page_header', trans('web::seat.queue_manage'))

@section('full')

  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-check-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_jobs') }}</span>
          <span class="info-box-number">{{ $totals['total_jobs'] }}</span>
          <a href="{{ route('queue.history') }}" class="btn btn-info btn-sm pull-right">
            {{ trans('web::seat.history') }}
          </a>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-truck"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.queued_jobs') }}</span>
          <span class="info-box-number">{{ $totals['queued_jobs'] }}</span>

          @if($totals['total_jobs'] > 0)
            {{
              number_format(((($totals['total_jobs'] - $totals['queued_jobs']) / $totals['total_jobs']) *100),2)
            }}% {{ trans('web::seat.complete') }}
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
          <span class="info-box-text">{{ trans('web::seat.working_jobs') }}</span>
          <span class="info-box-number">{{ $totals['working_jobs'] }}</span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-exclamation"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.error_jobs') }}</span>
          <span class="info-box-number">{{ $totals['error_jobs'] }}</span>
          <a href="{{ route('queue.errors') }}" class="btn btn-danger btn-sm pull-right">
            {{ trans_choice('web::seat.view', 1) }}
          </a>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
  </div>

  <div class="row">

    <div class="col-md-4">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.eve_api_status') }}</h3>
        </div>
        <div class="panel-body">

          <dl>
            <dt>{{ trans('web::seat.eve_api_status') }}</dt>
            <dd>
              @if(Cache::get(config('eveapi.config.cache_keys.down')))
                <span class="text-danger">
                  <b>{{ trans('web::seat.offline') }}</b>,
                  recheck at {{ Cache::get(config('eveapi.config.cache_keys.down_until')) }}
                </span>
              @else
                <span class="text-success">
                  {{ trans('web::seat.online') }}
                </span>
              @endif
            </dd>

            <dt>{{ trans('web::seat.eve_api_error_threshold') }}</dt>
            <dd>
              @if(!is_null(Cache::get(config('eveapi.config.cache_keys.api_error_count'))))
                {{ Cache::get(config('eveapi.config.cache_keys.api_error_count')) }}
              @else
                0
              @endif
                / {{ config('eveapi.config.limits.eveapi_errors')}}
            </dd>

            <dt>{{ trans('web::seat.eve_api_connection_threshold') }}</dt>
            <dd>
              @if(!is_null(Cache::get(config('eveapi.config.cache_keys.connection_error_count'))))
                {{ Cache::get(config('eveapi.config.cache_keys.connection_error_count')) }}
              @else
                0
              @endif
                / {{ config('eveapi.config.limits.connection_errors')}}
            </dd>
          </dl>

        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.submit_jobs') }}</h3>
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
          {{ trans('web::seat.job_submit_desc') }}
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-title">
            <i class="fa fa-heartbeat"></i>
            Supervisor Status
            <span class="pull-right label label-danger" id="supervisor-status">{{ trans('web::seat.supervisor_offline') }}</span>
          </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-2" id="supervisor-info">
                <span class="text-muted">It seems that supervisor is not running</span>
            </div>
            <div class="col-md-10">
              <table class="table table-condensed table-hover" id="supervisor-processes">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Process ID</th>
                    <th>Uptime</th>
                    <th>Log path</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-8">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            <i class="fa fa-exchange"></i>
            {{ trans('web::seat.working_jobs') }}
          </h3>
        </div>
        <div class="panel-body">

          @if(count($working) > 0)

            <table class="table table-condensed table-hover">
              <tbody>
              <tr>
                <th>{{ trans('web::seat.created') }}</th>
                <th>{{ trans('web::seat.updated') }}</th>
                <th>{{ trans('web::seat.owner_id') }}</th>
                <th>{{ trans('web::seat.api') }}</th>
                <th>{{ trans('web::seat.scope') }}</th>
                <th>{{ trans('web::seat.output') }}</th>
                <th>{{ trans('web::seat.status') }}</th>
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
                {{ trans('web::seat.no_working') }}
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
            {{ trans('web::seat.queued_jobs') }}
          </h3>
        </div>
        <div class="panel-body">

          @if(count($queued) > 0)

            <table class="table table-condensed table-hover">
              <tbody>
              <tr>
                <th>{{ trans('web::seat.created') }}</th>
                <th>{{ trans('web::seat.owner_id') }}</th>
                <th>{{ trans('web::seat.api') }}</th>
                <th>{{ trans('web::seat.scope') }}</th>
                <th>{{ trans('web::seat.status') }}</th>
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
                {{ trans('web::seat.no_queue') }}
              </span>

              @endif

              </tbody>
            </table>

        </div>
      </div>

    </div>

  </div>

@stop

@section('javascript')
  <script type="text/javascript" src="{{ asset('web/js/moment-with-locales.min.js') }}"></script>
  <script type="text/javascript">
    jQuery(document).ready(function(){
      var supervisorTable = jQuery('#supervisor-processes').DataTable({
        'ajax': {
          'url':'{{ route('json.supervisor.processes') }}',
          'dataSrc':''
        },
        'columns':[
          {data:'name'},
          {data:'pid'},
          {data:'start', render: human_readable},
          {data:'log'}
        ],
        'searching':false,
        'lengthChange':false
      });

      check_supervisor();

      setInterval(function(){
        supervisorTable.ajax.reload();
        check_supervisor();
      }, {{ config('web.config.queue_status_update_time') }});
    });

    function check_supervisor() {
      jQuery.ajax('{{ route('json.supervisor.status') }}', {
        success: function(data, textStatus, jqXHR){
          if (data.status) {
            if (jQuery('#supervisor-status').hasClass('label-danger')) {
              jQuery('#supervisor-status')
                      .removeClass('label-danger')
                      .addClass('label-success')
                      .text("{{ trans('web::seat.supervisor_online') }}");

              jQuery.ajax('{{ route('queue.supervisor.information') }}', {
                success: function(data, textStatus, jqXHR){
                  jQuery('#supervisor-info').html(data);
                }
              });
            }
          } else {
            if (jQuery('#supervisor-status').hasClass('label-success')) {
              jQuery('#supervisor-status')
                      .removeClass('label-success')
                      .addClass('label-danger')
                      .text("{{ trans('web::seat.supervisor_offline') }}");

              jQuery('#supervisor-info').html('<span class="text-muted">It seems that supervisor is not running</span>');
            }
          }
        }
      });
    }

    /*
     * This function is used by datatable in order to mimic Laravel Carbon human_readable helper
     */
    function human_readable(data, type, row){
      if (type == 'display') {
        var date = moment(data, "YYYY-MM-DD HH:mm:ss").fromNow();
        return '<span data-toggle="tooltip" data-placement="top" title="' + data + '">' + date + "</span>";
      }
      return data;
    }
  </script>
@stop