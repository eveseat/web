@extends('web::layouts.grids.3-9')

@section('title', trans('web::seat.error_jobs'))
@section('page_header', trans('web::seat.error_jobs'))
@section('page_description', count($job_errors))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.actions') }}</h3>
    </div>
    <div class="panel-body">

      <a href="{{ route('queue.errors.clear') }}" class="btn btn-danger btn-xs btn-block confirmlink">
        {{ trans('web::seat.clear_all_errors') }}
      </a>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.error_jobs') }}</h3>
    </div>
    <div class="panel-body">

      @if(count($job_errors) > 0)

        <table class="table table-condensed table-hover">
          <tbody>
          <tr>
            <th>{{ trans('web::seat.status') }}</th>
            <th>{{ trans('web::seat.owner_id') }}</th>
            <th>{{ trans('web::seat.created') }}</th>
            <th>{{ trans('web::seat.api') }}</th>
            <th>{{ trans('web::seat.scope') }}</th>
            <th>{{ trans('web::seat.last_output') }}</th>
          </tr>

          @foreach($job_errors as $job_error)

            <tr>
              <td>
                <span class="label label-danger">
                  {{ ucfirst($job_error->status) }}
                </span>
              </td>
              <td>{{ $job_error->owner_id }}</td>
              <td>{{ $job_error->created_at }}</td>
              <td>{{ $job_error->api }}</td>
              <td>{{ $job_error->scope }}</td>
              <td>{{ str_limit($job_error->output, 50, '...') }}</td>
              <td>
                <a href="{{ route('queue.errors.detail', ['job_id' => $job_error->job_id]) }}"
                   class="btn btn-xs btn-primary">
                  {{ trans('web::seat.view_full') }}
                </a>
              </td>
            </tr>

          @endforeach

          </tbody>
        </table>

      @else

        <span class="text-muted">
          {{ trans('web::seat.no_errors') }}
        </span>

      @endif

    </div>
  </div>

@stop
