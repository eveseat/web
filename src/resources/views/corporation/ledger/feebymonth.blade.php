@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'feebymonth'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.feebymonth', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.feebymonth', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Available Ledgers</h3>
    </div>
    <div class="panel-body">

      @foreach ($fee->chunk(3) as $chunk)
        <div class="row">

          @foreach ($chunk as $fee)
            <div class="col-xs-4">
              <span class="text-bold">
                <a href="{{ route('corporation.view.ledger.feebymonth', ['corporation_id' => $corporation_id, 'year' => $fee->year, 'month' => $fee->month]) }}">
                  {{ date("M Y", strtotime($fee->year . "-" . $fee->month . "-01")) }}
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
      <h3 class="panel-title">{{ trans_choice('web::seat.feebymonth', 2) }}
        - {{ date("M Y", strtotime($year . "-" . $month . "-01")) }}</h3>
    </div>

    <div class="panel-body">
      <div>
        <table class="table datatable table-condensed table-hover table-responsive">
          <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.feetotal', 1) }}</th>
          </tr>
          </thead>
          <tbody>

          @foreach ($feedates as $fbm)
            <tr>
              <td data-order="{{ $fbm->ownerName2 }}">
                <a href="{{ route('character.view.sheet', ['character_id' => $fbm->ownerID2]) }}">
                  @if ($fbm->owner2TypeID == 2)
                  {!! img('corporation', $fbm->ownerID2, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  @else
                  {!! img('character', $fbm->ownerID2, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  @endif
                  {{ $fbm->ownerName2 }}
                </a>
              </td>
              <td data-order="{{ number($fbm->total) }}">{{ number($fbm->total) }}</td>
            </tr>
          @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="panel-footer">
      <h3 class="panel-title">Total: {{ number($feedates->sum('total')) }}</h3>
    </div>
  </div>

@stop
