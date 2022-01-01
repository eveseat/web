@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.standings_builder'))
@section('page_header', trans('web::seat.standings_builder'))
@section('page_description', trans('web::seat.standings_builder'))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">New Standings Definition</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('seatcore::tools.standings.new') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label">Name</label>
            <div class="col-md-8">
              <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                     placeholder="Standings Definition Name">
            </div>
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

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Current Standings</h3>
    </div>
    <div class="card-body">

      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Standings Defined</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

          @foreach($standings as $standing)

            <tr>
              <td>{{ $standing->name }}</td>
              <td>{{ $standing->entities->count() }}</td>
              <td>
                <div class="btn-group btn-group-sm float-right">
                  <a href="{{ route('seatcore::tools.standings.edit', ['id' => $standing->id]) }}" type="button"
                     class="btn btn-warning">
                    <i class="fas fa-pencil-alt"></i>
                    {{ trans('web::seat.edit') }}
                  </a>
                  <a href="{{ route('seatcore::tools.standings.delete', ['profile_id' => $standing->id]) }}" type="button"
                     class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i>
                    {{ trans('web::seat.delete') }}
                  </a>
                </div>
              </td>
            </tr>

          @endforeach

        </tbody>
      </table>

    </div>
    <div class="card-footer">
      <i class="text-muted float-right">{{ $standings->count() }} Standings</i>
    </div>
  </div>

@stop
