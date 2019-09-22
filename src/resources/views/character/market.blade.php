@extends('web::character.layouts.view', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.market') }}
        <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.market']) }}" class="pull-right">
          <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_market') }}"></i>
        </a>
      </h3>
    </div>
    <div class="panel-body">
      <div class="margin-bottom">
        <select multiple="multiple" id="dt-character-selector" class="form-control">
          @foreach($characters as $character)
            @if($character->id == $request->character_id)
              <option selected="selected" value="{{ $character->id }}">{{ $character->name }}</option>
            @else
              <option value="{{ $character->id }}">{{ $character->name }}</option>
            @endif
          @endforeach
        </select>
      </div>

      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  <script>
    $(document).ready(function() {
        $('#dt-character-selector')
            .select2()
            .on('change', function () {
                window.LaravelDataTables['dataTableBuilder'].ajax.reload();
            });
    });
  </script>
@endpush
