@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'industry_facility', 'breadcrumb' => trans('web::seat.industry_facility')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.industry_facility'))

@section('ledger_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Available Ledgers</h3>
    </div>
    <div class="card-body">

      @foreach ($periods->chunk(12) as $chunk)
        <ul class="nav justify-content-between">

          @foreach ($chunk as $period)
            <li class="nav-item">
              <a href="{{ route('corporation.view.ledger.industry_facility', ['corporation_id' => $corporation_id, 'year' => $period->year, 'month' => $period->month]) }}" class="nav-link">
                {{ date("M Y", strtotime(sprintf('%d-%d-01', $period->year, $period->month))) }}
              </a>
            </li>
          @endforeach

        </ul>
      @endforeach

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.industry_facility', 2) }}
        - {{ date("M Y", strtotime(sprintf('%d-%d-01', $year, $month))) }}</h3>
    </div>
    <div class="card-body">
      <table class="table datatable table-sm table-condensed table-hover table-striped">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans('web::seat.total') }}</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($entries as $entry)

            <tr>
              <td data-order="{{ $entry->first_party->name }}">
                @switch($entry->first_party->category)
                  @case('character')
                    @include('web::partials.character', ['character' => $entry->first_party])
                  @break
                  @case('corporation')
                    @include('web::partials.corporation', ['corporation' => $entry->first_party])
                  @break
                  @case('alliance')
                    @include('web::partials.alliance', ['alliance' => $entry->first_party])
                  @break
                @endswitch
              </td>
              <td data-order="{{ $entry->total }}">{{ number_format($entry->total) }}</td>
            </tr>

          @endforeach

        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <i>Total: {{ number_format($entries->sum('total')) }}</i>
    </div>
  </div>

@stop
