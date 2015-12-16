@extends('web::layouts.grids.3-9')

@section('title', trans('web::seat.schedule'))
@section('page_header', trans('web::seat.schedule'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.new_schedule') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.schedule.new') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="command">{{ trans('web::seat.available_commands') }}</label>
            <select name="command" id="available_commands" style="width: 100%">

              @foreach($commands as $name => $data)

                <option value="{{ $name }}"
                        @if($name == old('command'))
                          selected
                        @endif
                        >
                  {{ $name }}
                </option>

              @endforeach

            </select>
          </div>

          <div class="form-group">
            <label for="expression">{{ trans('web::seat.cron_expression') }}</label>
            <select name="expression" id="available_expressions" style="width: 100%">

              @foreach($expressions as $name => $expression)

                <option value="{{ $expression }}"
                        @if($expression == old('expression'))
                          selected
                        @endif
                        >
                  {{ $name }}
                </option>

              @endforeach

            </select>
            <span class="help-block">
              <span id="expression"></span><br>
              {{ trans('web::seat.choose_prepop') }}
            </span>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('web::seat.add_scheduled') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.current_schedule') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.command') }}</th>
          <th>{{ trans('web::seat.cron') }}</th>
          <th>{{ trans('web::seat.allow_overlap') }}</th>
          <th>{{ trans('web::seat.allow_maintenance') }}</th>
          <th></th>
        </tr>

        @foreach($schedule as $job)

          <tr>
            <td>{{ $job->command }}</td>
            <td>{{ $job->expression }}</td>
            <td>{{ $job->allow_overlap }}</td>
            <td>{{ $job->allow_maintenance }}</td>
            <td>
              <a href="{{ route('configuration.schedule.delete', ['schedule_id' => $job->id]) }}" type="button"
                class="btn btn-danger btn-xs confirmlink">
                {{ trans('web::seat.delete') }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($schedule) }} {{ trans('web::seat.scheduled_commands') }}
    </div>
  </div>

@stop

@section('javascript')

  <script>
    $("#available_commands, #available_expressions").select2({
      tags: true
    });
  </script>

  <script>
    $("#available_expressions").on("change", function() {
      $("span#expression").html(
              "Cron: <b>" + this.value.replace("<", "") + "</b>");
    });
  </script>

@stop
