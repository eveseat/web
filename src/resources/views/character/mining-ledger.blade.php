@extends('web::character.layouts.view', ['viewname' => 'mining-ledger', 'breadcrumb' => trans('web::seat.mining')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mining'))

@inject('request', Illuminate\Http\Request')

@section('character_content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.mining') }}</h3>
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

  @include('web::common.minings.modals.details.details')
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

          $('#mining-detail').on('show.bs.modal', function (e) {
              var body = $(e.target).find('.modal-body');
              body.html('Loading...');

              $.ajax($(e.relatedTarget).data('url'))
                  .done(function (data) {
                      body.html(data);
                      $("[data-toggle=tooltip]").tooltip();
                  });
          });
      });
  </script>
@endpush
