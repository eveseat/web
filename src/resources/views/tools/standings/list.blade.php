@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.standings_builder'))
@section('page_header', trans('web::seat.standings_builder'))
@section('page_description', trans('web::seat.standings_builder'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Standings Definition</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('tools.standings.new') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                   placeholder="Standings Definition Name">
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary float-right">
            Add
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Current Standings</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Name</th>
          <th>Standings Defined</th>
          <th>Edit</th>
        </tr>

        @foreach($standings as $standing)

          <tr>
            <td>{{ $standing->name }}</td>
            <td>{{ $standing->standings->count() }}</td>
            <td>
              <a href="{{ route('tools.standings.edit', ['id' => $standing->id]) }}" type="button"
                 class="btn btn-primary btn-xs">
                {{ trans('web::seat.edit') }}
              </a>
              <a href="{{ route('tools.standings.delete', ['profile_id' => $standing->id]) }}" type="button"
                 class="btn btn-danger btn-xs">
                {{ trans('web::seat.delete') }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ $standings->count() }} Standings
    </div>
  </div>

@stop
