@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.standings_builder'))
@section('page_header', trans('web::seat.standings_builder'))
@section('page_description', trans('web::seat.standings_builder'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Standings Entry</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('tools.standings.edit.addelement') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $id }}">

        <div class="box-body">

          <div class="form-group">
            <label for="name">Name</label>
            <select id="element-name" name="element_id" style="width: 100%"></select>
          </div>

          <div class="form-group">
            <label for="text">Type</label>
            <select id="element-type" name="type" style="width: 100%">
              <option value="character">Character</option>
              <option value="corporation">Corporation</option>
              <option value="alliance">Alliance</option>
            </select>
          </div>

          <div class="form-group">
            <label for="text">Standing</label>
            <select id="element-standing" name="standing" style="width: 100%">
              <option value="-10">-10</option>
              <option value="-5">-5</option>
              <option value="0">0</option>
              <option value="5">5</option>
              <option value="10">10</option>
            </select>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
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
          <th>Type</th>
          <th>Name</th>
          <th>Standing</th>
        </tr>

        @foreach($standing->standings as $standing)

          <tr>
            <td>{{ ucfirst($standing->type) }}</td>
            <td><span rel="id-to-name">{{ $standing->elementID }}</span></td>
            <td>{{ $standing->standing }}</td>
            <td>
              <a href="{{ route('configuration.access.roles.delete', ['id' => $standing->id]) }}" type="button"
                 class="btn btn-danger btn-xs">
                {{ trans('web::seat.delete') }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop

@section('javascript')

  <script>

    // Resolve names to EVE IDs
    $("select#element-name").select2({
      ajax: {
        url: '{{ route("tools.standings.ajax.element") }}',
        dataType: 'json',
        type: 'POST',
        delay: 250,
        cache: true
      },
    });

    $("select#element-type,select#element-standing").select2();

  </script>

  @include('web::includes.javascript.id-to-name')
@stop
