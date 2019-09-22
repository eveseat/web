@extends('web::character.wallet.layouts.view', ['sub_viewname' => 'journal', 'breadcrumb' => trans('web::seat.wallet_journal')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Spending Graph</h3>
        </div>
        <div class="panel-body">

          <canvas id="balance-over-time" style="height: 249px; width: 555px;" height="200" width="1110"></canvas>

        </div>
      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.wallet_journal') }}</h3>
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

  <script type="text/javascript">
    // ChartJS Spending Graph
    $.get("{{ route('character.view.journal.graph.balance', ['character_id' => $request->character_id]) }}", function (data) {

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
