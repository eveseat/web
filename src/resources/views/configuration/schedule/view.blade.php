@extends('web::layouts.grids.3-9')

@section('title', 'Schedule')
@section('page_header', 'Schedule')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Schedule</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.schedule.new') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="command">Available Commands</label>
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
            <label for="expression">Cron Expression</label>
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
              Choose a pre-populated cron expression, or write your own.
            </span>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            Add Scheduled Command
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Current Schedule</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Command</th>
          <th>Cron</th>
          <th>Allow Overlap</th>
          <th>Allow in Maintenance</th>
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
                {{ ucfirst(trans('web::general.delete')) }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($schedule) }} scheduled commands
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
