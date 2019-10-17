@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'bountyprizesbymonth', 'breadcrumb' => trans_choice('web::seat.bountyprizesbymonth', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.bountyprizesbymonth', 2))

@section('ledger_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Available Ledgers</h3>
    </div>
    <div class="card-body">

      @foreach ($ledgers->chunk(12) as $chunk)
        <ul class="nav justify-content-between">

          @foreach ($chunk as $period)
            <li class="nav-item">
              <a href="{{ route('corporation.view.ledger.bountyprizesbymonth', ['corporation_id' => $corporation_id, 'year' => $period->year, 'month' => $period->month]) }}" class="nav-link">
                {{ date("M Y", strtotime($period->year."-".$period->month."-01")) }}
              </a>
            </li>
          @endforeach

        </ul>
      @endforeach
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.bountyprizesbymonth', 2) }}
        - {{ date("M Y", strtotime($year."-".$month."-01")) }}</h3>
    </div>

    <div class="card-body">
      <table class="table datatable table-sm table-condensed table-striped table-hover">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.bountyprizetotal', 1) }}</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($bounty_prizes as $bounty_prize)
            <tr>
              <td data-order="{{ $bounty_prize->second_party_id }}">
                @include('web::partials.character', ['character' => $bounty_prize->second_party])
              </td>
              <td data-order="{{ $bounty_prize->total }}">{{ number($bounty_prize->total) }}</td>
            </tr>
          @endforeach

        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <i>Total: {{ number($bounty_prizes->sum('total')) }}</i>
    </div>
  </div>

@stop
