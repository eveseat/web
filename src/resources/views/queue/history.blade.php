@extends('web::layouts.grids.3-9')

@section('title', 'Job History')
@section('page_header', 'Job History')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::queue.actions') }}</h3>
    </div>
    <div class="panel-body">

      <a href="{{ route('queue.history.clear') }}" class="btn btn-danger btn-xs btn-block confirmlink">
        {{ trans('web::queue.clear_all_history') }}
      </a>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::queue.job_history') }}</h3>
    </div>
    <div class="panel-body">

      @if(count($history) > 0)
        <table class="table table-condensed table-hover">
          <tbody>
          <tr>
            <th>{{ trans('web::queue.status') }}</th>
            <th>{{ trans('web::queue.owner_id') }}</th>
            <th>{{ trans('web::queue.created') }}</th>
            <th>{{ trans('web::queue.api') }}</th>
            <th>{{ trans('web::queue.scope') }}</th>
            <th>{{ trans('web::queue.output') }}</th>
          </tr>

          @foreach($history as $job)

            <tr>
              <td>{{ ucfirst($job->status) }}</td>
              <td>{{ $job->owner_id }}</td>
              <td>{{ $job->created_at }}</td>
              <td>{{ $job->api }}</td>
              <td>{{ $job->scope }}</td>
              <td>{{ str_limit($job->output, 50, '...') }}</td>
            </tr>

          @endforeach

          </tbody>
        </table>

      @else

        <span class="text-muted">
          {{ trans('web::queue.no_history') }}
        </span>

      @endif

    </div>

    @if(count($history) == 20)
      <div class="panel-footer">
        {!! $history->render() !!}
      </div>
    @endif
  </div>

@stop
