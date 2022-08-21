@extends('web::layouts.character', ['viewname' => 'journal', 'breadcrumb' => trans('web::seat.wallet_journal')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

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
        <div class="card-header d-flex align-items-center">
          <div class="col-auto me-5">
            <h3 class="card-title">{{ trans('web::seat.wallet_journal') }}</h3>
          </div>
          <div class="col-6">
            @include('web::character.includes.dt-character-selector')
          </div>
          <div class="ms-auto">
            @if($character->refresh_token)
              @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.journals', 'label' => trans('web::seat.update_journals')])
            @endif
          </div>
        </div>

          {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
      </div>

    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

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
