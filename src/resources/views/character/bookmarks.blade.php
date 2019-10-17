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
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.bookmarks']) }}"
             class="text-dark">
            <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_bookmarks') }}"></i>
          </a>
        </div>
      @endif
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