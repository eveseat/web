@extends('web::layouts.character', ['viewname' => 'transactions', 'breadcrumb' => trans('web::seat.wallet_transactions')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            {{ trans('web::seat.wallet_transactions') }}
          </h3>
          @if($character->refresh_token)
          <div class="card-tools">
            <div class="input-group input-group-sm">
              @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.transactions', 'label' => trans('web::seat.update_transactions')])
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

    </div>
  </div>

@stop

@push('javascript')
  <script src="{{ asset('web/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('web/js/buttons.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

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
