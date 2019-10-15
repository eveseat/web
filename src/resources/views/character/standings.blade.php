@extends('web::character.layouts.view', ['viewname' => 'standings', 'breadcrumb' => trans_choice('web::seat.standings', 0)])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans_choice('web::seat.standings', 0))

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.standings', 0) }}</h3>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control">
          @foreach($characters as $character)
            @if($character->id == request()->character_id)
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