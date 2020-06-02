@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.standings_builder'))
@section('page_header', trans('web::seat.standings_builder'))
@section('page_description', $standing->name)

@inject('request', 'Illuminate\Http\Request')

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Add from Entity Contact</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('tools.standings.edit.addelement.fromcorpchar') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $request->id }}">

        <div class="box-body">

          <div class="form-group row">
            <label for="characterstanding" class="col-form-label col-md-4">Characters</label>
            <div class="col-md-8">
              <select id="characterstanding" name="character" style="width: 100%;"></select>
            </div>
          </div>

          <div class="form-group row">
            <label for="corporationstanding" class="col-form-label col-md-4">Corporations</label>
            <div class="col-md-8">
              <select id="corporationstanding" name="corporation" style="width: 100%;"></select>
            </div>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-success float-right">
            <i class="fas fa-plus-square"></i>
            Add
          </button>
        </div>
      </form>

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Add Single Entry</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('tools.standings.edit.addelement') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $request->id }}">
        <input type="hidden" name="name" id="entity_name" value="" />

        <div class="box-body">

          <div class="form-group row">
            <label for="element-name" class="col-form-label col-md-4">Name</label>
            <div class="col-md-8">
              <select id="element-name" name="entity_id" style="width: 100%;"></select>
            </div>
          </div>

          <div class="form-group row">
            <label for="element-type" class="col-form-label col-md-4">Type</label>
            <div class="col-md-8">
              <select id="element-type" name="type" style="width: 100%;">
                <option value="character">Character</option>
                <option value="corporation">Corporation</option>
                <option value="alliance">Alliance</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="element-standing" class="col-form-label col-md-4">Standing</label>
            <div class="col-md-8">
              <select id="element-standing" name="standing" style="width: 100%;">
                <option value="-10">-10</option>
                <option value="-5">-5</option>
                <option value="0">0</option>
                <option value="5">5</option>
                <option value="10">10</option>
              </select>
            </div>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-success float-right">
            <i class="fas fa-plus-square"></i>
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

      <table class="table table-sm table-condensed table-hover">
        <thead>
          <tr>
            <th>Type</th>
            <th>Name</th>
            <th>Standing</th>
          </tr>
        </thead>
        <tbody>

          @foreach($standing->entities->sortByDesc('pivot.standing') as $entity)

            <tr class="
              @if($entity->pivot->standing > 0)
                table-success
              @elseif($entity->pivot->standing < 0)
                table-danger
              @endif
            ">
              <td>{{ ucfirst($entity->category) }}</td>
              <td>
                @switch($entity->category)
                  @case('character')
                    @include('web::partials.character', ['character' => $entity])
                    @break
                  @case('corporation')
                    @include('web::partials.corporation', ['corporation' => $entity])
                    @break
                  @case('alliance')
                    @include('web::partials.alliance', ['alliance' => $entity])
                    @break
                @endswitch
              </td>
              <td>{!! view('web::partials.standing', ['standing' => $entity->pivot->standing]) !!}</td>
              <td>
                <a href="{{ route('tools.standings.edit.remove', ['entity_id' => $entity->entity_id, 'profile_id' => $request->id]) }}"
                   type="button" class="btn btn-danger btn-sm">
                  <i class="fas fa-trash-alt"></i>
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
  }).on('select2:select', function (e) {
      $('#entity_name').val(e.params.data.text ?? '');
  });

  $("select#element-type," + "select#element-standing").select2();

  $("select#characterstanding").select2({
    placeholder: "{{ trans('web::seat.select_item_add') }}",
    ajax: {
      url: "{{ route('fastlookup.characters') }}",
      dataType: 'json',
      cache: true,
    },
    minimumInputLength: 3
  })

  $("select#corporationstanding").select2({
    placeholder: "{{ trans('web::seat.select_item_add') }}",
    ajax: {
      url: "{{ route('fastlookup.corporations') }}",
      dataType: 'json',
      cache: true,
    },
    minimumInputLength: 3
  })

</script>

@include('web::includes.javascript.id-to-name')

@endpush
