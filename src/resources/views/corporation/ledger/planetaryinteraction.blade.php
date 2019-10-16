@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'planetaryinteraction', 'breadcrumb' => trans_choice('web::seat.pi', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.pi', 2))

@section('ledger_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Available Ledgers</h3>
    </div>
    <div class="card-body">

      @foreach ($ledgers->chunk(12) as $chunk)
        <ul class="nav justify-content-between">

          @foreach ($chunk as $prize)
            <li class="nav-item">
              <a href="{{ route('corporation.view.ledger.planetaryinteraction', ['corporation_id' => $corporation_id, 'year' => $prize->year, 'month' => $prize->month]) }}" class="nav-link">
                {{ date("M Y", strtotime($prize->year."-".$prize->month."-01")) }}
              </a>
            </li>
          @endforeach

        </ul>
      @endforeach

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.pi', 2) }}
        - {{ date("M Y", strtotime($year."-".$month."-01")) }}</h3>
    </div>
    <div class="card-body">
      <table class="table datatable table-sm table-condensed table-hover table-striped">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.pitotals', 1) }}</th>
          </tr>
        </thead>
        <tbody>

        @foreach ($pi_taxes as $tax)

          <tr>
            <td data-order="{{ $tax->first_party_id }}">
              @include('web::partials.character', ['character' => $tax->first_party])
            </td>
            <td data-order="{{ number($tax->total) }}">{{ number($tax->total) }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <i>Total: {{ number($pi_taxes->sum('total')) }}</i>
    </div>
  </div>

@stop
