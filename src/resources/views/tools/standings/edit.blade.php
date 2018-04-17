@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.standings_builder'))
@section('page_header', trans('web::seat.standings_builder'))
@section('page_description', $standing->name)

@inject('request', 'Illuminate\Http\Request')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Add from Entity Contact</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('tools.standings.edit.addelement.fromcorpchar') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $request->id }}">

        <div class="box-body">

          <div class="form-group">
            <label for="characterstanding">Characters</label>
            <select id="characterstanding" name="character" style="width: 100%">
              <option></option>
              @foreach($characters as $character)

                <option value="{{ $character->character_id }}">{{ $character->name }}</option>

              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="corporationstanding">Corporations</label>
            <select id="corporationstanding" name="corporation" style="width: 100%">
              <option></option>
              @foreach($corporations as $corporation)

                <option value="{{ $corporation->corporation_id }}">{{ $corporation->name }}</option>

              @endforeach
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

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Add Single Entry</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('tools.standings.edit.addelement') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $request->id }}">

        <div class="box-body">

          <div class="form-group">
            <label for="element-name">Name</label>
            <select id="element-name" name="element_id" style="width: 100%"></select>
          </div>

          <div class="form-group">
            <label for="element-type">Type</label>
            <select id="element-type" name="type" style="width: 100%">
              <option value="character">Character</option>
              <option value="corporation">Corporation</option>
              <option value="alliance">Alliance</option>
            </select>
          </div>

          <div class="form-group">
            <label for="element-standing">Standing</label>
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

        @foreach($standing->standings->sortByDesc('standing') as $standing)

          <tr class="
            @if($standing->standing > 0)
                  success
                @elseif($standing->standing < 0)
                  danger
                @endif
                  ">
            <td>{{ ucfirst($standing->type) }}</td>
            <td>
              {!! img('auto', $standing->elementID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span rel="id-to-name">{{ $standing->elementID }}</span>
            </td>
            <td>{{ $standing->standing }}</td>
            <td>
              <a href="{{ route('tools.standings.edit.remove', ['element_id' => $standing->id, 'profile_id' => $request->id]) }}"
                 type="button" class="btn btn-danger btn-xs">
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

@push('javascript')

<script>

  // Resolve names to EVE IDs
  $("select#element-name").select2({
    ajax: {
      url     : '{{ route("tools.standings.ajax.element") }}',
      dataType: 'json',
      type    : 'POST',
      delay   : 250,
      cache   : true,
      data    : function(params){
          return {
              search: params.term,
              type: $('#element-type').val()
          }
      }
    }
  });

  $("select#element-type," + "select#element-standing," +
      "select#characterstanding," +
      "select#corporationstanding").select2();

</script>

@include('web::includes.javascript.id-to-name')

@endpush
