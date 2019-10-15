@extends('web::character.layouts.view', ['viewname' => 'killmails', 'breadcrumb' => trans('web::seat.killmails')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.killmails'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.killmails') }}
      </h3>
      <div class="card-tools">
        <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.killmails']) }}" class="float-right">
          <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_killmails') }}"></i>
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-3">
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
