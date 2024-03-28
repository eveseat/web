@extends('web::character.wallet.layouts.view', ['sub_viewname' => 'journal', 'breadcrumb' => trans('web::seat.wallet_journal')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Spending Graph</h3>
        </div>
        <div class="card-body">

          <canvas id="balance-over-time" style="height: 249px; width: 555px;" height="200" width="1110"></canvas>

        </div>
      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans('web::seat.wallet_journal') }}</h3>
          @if($character->refresh_token)
          <div class="card-tools">
            <div class="input-group input-group-sm">
              @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.journals', 'label' => trans('web::seat.update_journals')])
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
  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

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

  <script type="text/javascript">
    // ChartJS Spending Graph
    $.get("{{ route('seatcore::character.view.journal.graph.balance', ['character' => $request->character]) }}", function (data) {

      new Chart($("canvas#balance-over-time"), {
        type   : 'line',
        data   : data,
        options: {
          tooltips: {
            mode: 'index'
          },

          scales: {
            xAxes: [{
              display: false
            }]
          }
        }
      });
    });
  </script>

@endpush
