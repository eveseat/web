@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'sellbymonth'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.sellbymonth', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.sellbymonth', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Available Ledgers</h3>
    </div>
    <div class="panel-body">

      @foreach ($sell->chunk(3) as $chunk)
        <div class="row">

          @foreach ($chunk as $sell)
            <div class="col-xs-4">
              <span class="text-bold">
                <a href="{{ route('corporation.view.ledger.sellbymonth', ['corporation_id' => $corporation_id, 'year' => $sell->year, 'month' => $sell->month]) }}">
                  {{ date("M Y", strtotime($sell->year . "-" . $sell->month . "-01")) }}
                </a>
              </span>
            </div>
          @endforeach

        </div>
      @endforeach
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.sellbymonth', 2) }}
        - {{ date("M Y", strtotime($year . "-" . $month . "-01")) }}</h3>
    </div>

    <div class="panel-body">
      <div>
        <table class="table datatable table-condensed table-hover table-responsive">
          <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.selltotal', 1) }}</th>
          </tr>
          </thead>
          <tbody>

          @foreach ($selldates as $sbm)
            <tr>
              <td data-order="{{ $sbm->ownerName2 }}">
                <a href="{{ route('character.view.sheet', ['character_id' => $sbm->ownerID2]) }}">
                  @if ($sbm->owner2TypeID == 2)
                  {!! img('corporation', $sbm->ownerID2, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  @else
                  {!! img('character', $sbm->ownerID2, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  @endif
                  {{ $sbm->ownerName2 }}
                </a>
              </td>
              <td data-order="{{ number($sbm->total) }}">{{ number($sbm->total) }}</td>
            </tr>
          @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="panel-footer">
      <h3 class="panel-title">Total: {{ number($selldates->sum('total')) }}</h3>
    </div>
  </div>

@stop
