@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.worker_constraints'))
@section('page_header', trans('web::seat.worker_constraints'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Description</h3>
    </div>
    <div class="panel-body">
      <p>
        Worker Constraints are designed to allow you to limit which workers
        the SeAT EVE API updater will invoke. Constraints are imposed by
        selecting the groups you would like to run as part of the updater process.
      </p>
      <p>
        A single group may contain more than one actual updater as some updating
        logic in the backend may depend on more than one Class to work.
      </p>
      <p>
        All of the configuration on this page applies to <b>all</b> updaters. It is
        possible to limit the worker constraints for a single key by navigating to
        the key in question as a user with the <i>superuser</i> role.
      </p>
      <p>
        <b>
          Note: Not selecting any constraints implies that *all* updaters will be run.
        </b>
      </p>
    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Global Worker Constraints</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('workers.constraints.update') }}" method="post">
        {{ csrf_field() }}

        <table class="table compact table-condensed table-hover table-responsive">
          <tbody>
          @foreach($available as $type => $groups)

            <tr class="active">
              <td colspan="3">
                <b>{{ ucfirst($type) }}</b>

                <span class="pull-right">
                  <i>
                    @if(!is_null($current) && array_key_exists($type, $current) && !is_null($current[$type]) && count($current[$type]) > 0)
                      {{ count($current[$type]) }} Constraints!
                    @else
                      All workers for {{ $type }} will run.
                    @endif
                  </i>
                </span>
              </td>
            </tr>

            @foreach($groups as $group => $classes)

              <tr>
                <td>{{ ucfirst($group) }}</td>
                <td>{{ count($classes) }}</td>
                <td>

                  <div class="checkbox-inline">
                    <label>

                      @if(!is_null($current) && array_key_exists($type, $current))

                        @if(!is_null($current[$type]) && in_array($group, $current[$type]))

                          <input type="checkbox" name="{{ $type.'[]' }}" value="{{ $group }}" checked>

                        @else

                          <input type="checkbox" name="{{ $type.'[]' }}" value="{{ $group }}">

                        @endif

                      @else

                        <input type="checkbox" name="{{ $type.'[]' }}" value="{{ $group }}">

                      @endif
                      {{ trans('web::seat.enabled') }}

                    </label>
                  </div>

                </td>
              </tr>

            @endforeach

          @endforeach
          </tbody>
        </table>

        <div class="box-footer">
          <button type="submit" class="btn btn-primary">
            {{ trans('web::seat.update') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop
