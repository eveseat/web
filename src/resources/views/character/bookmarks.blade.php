@extends('web::character.layouts.view', ['viewname' => 'bookmarks', 'breadcrumb' => trans_choice('web::seat.bookmark', 2)])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans_choice('web::seat.bookmark', 2))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans_choice('web::seat.bookmark', 2) }}
      </h3>
      @if(auth()->user()->has('character.jobs'))
        <div class="card-tools">
          <div class="input-group input-group-sm">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.bookmarks']) }}"
               class="btn btn-sm btn-light">
              <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_bookmarks') }}"></i>
            </a>
          </div>
        </div>
      @endif
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control" style="width: 100%;">
          @foreach($characters as $character)
            @if($character->character_id == $request->character_id)
              <option selected="selected" value="{{ $character->character_id }}">{{ $character->name }}</option>
            @else
              <option value="{{ $character->character_id }}">{{ $character->name }}</option>
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