@extends('web::character.layouts.view', ['viewname' => 'mining-ledger', 'breadcrumb' => trans('web::seat.mining')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mining'))

@inject('request', Illuminate\Http\Request')

@section('character_content')
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.mining') }}</h3>
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

      {{ $dataTable->table() }}
    </div>
  </div>

  @include('web::character.includes.mining-ledger-modal')
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
