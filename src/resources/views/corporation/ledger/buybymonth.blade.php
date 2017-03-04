@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'buybymonth'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.buybymonth', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.buybymonth', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Available Ledgers</h3>
    </div>
    <div class="panel-body">

      @foreach ($buy->chunk(3) as $chunk)
        <div class="row">

          @foreach ($chunk as $buy)
            <div class="col-xs-4">
              <span class="text-bold">
                <a href="{{ route('corporation.view.ledger.buybymonth', ['corporation_id' => $corporation_id, 'year' => $buy->year, 'month' => $buy->month]) }}">
                  {{ date("M Y", strtotime($buy->year . "-" . $buy->month . "-01")) }}
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
      <h3 class="panel-title">{{ trans_choice('web::seat.buybymonth', 2) }}
        - {{ date("M Y", strtotime($year . "-" . $month . "-01")) }}</h3>
    </div>

    <div class="panel-body">
      <div>
        <table class="table datatable table-condensed table-hover table-responsive">
          <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.buytotal', 1) }}</th>
          </tr>
          </thead>
          <tbody>

          @foreach ($buydates as $bbm)
            <tr>
              <td data-order="{{ $bbm->ownerName2 }}">
                <a href="{{ route('character.view.sheet', ['character_id' => $bbm->ownerID2]) }}">
                  @if ($bbm->owner2TypeID == 2)
                  {!! img('corporation', $bbm->ownerID2, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  @else
                  {!! img('character', $bbm->ownerID2, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  @endif
                  {{ $bbm->ownerName2 }}
                </a>
              </td>
              <td data-order="{{ number($bbm->total) }}">{{ number($bbm->total) }}</td>
            </tr>
          @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="panel-footer">
      <h3 class="panel-title">Total: {{ number($buydates->sum('total')) }}</h3>
    </div>
  </div>

@stop
