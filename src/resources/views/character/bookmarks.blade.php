@extends('web::character.layouts.view', ['viewname' => 'bookmarks', 'breadcrumb' => trans_choice('web::seat.bookmark', 2)])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans_choice('web::seat.bookmark', 2))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans_choice('web::seat.bookmark', 2) }}
      </h3>
      @if($character->refresh_token)
      <div class="card-tools">
        <div class="input-group input-group-sm">
          <button type="button" class="btn btn-sm btn-light" data-widget="esi-update" data-character="{{ $character->character_id }}" data-job="character.bookmarks">
            <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_bookmarks') }}"></i>
          </button>
        </div>
      </div>
      @endif
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control" style="width: 100%;">
          @if($character->refresh_token)
            @foreach($character->refresh_token->user->characters as $character_info)
              @if($character_info->character_id == $character->character_id)
                <option selected="selected" value="{{ $character_info->character_id }}">{{ $character_info->name }}</option>
              @else
                <option value="{{ $character_info->character_id }}">{{ $character_info->name }}</option>
              @endif
            @endforeach
          @else
            <option selected="selected" value="{{ $character->character_id }}">{{ $character->name }}</option>
          @endif
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