@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'summary', 'breadcrumb' => trans_choice('web::seat.wallet_divisions', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.wallet_divisions', 2))

@section('ledger_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.wallet_divisions', 2) }}</h3>
    </div>
    <div class="card-body">
      <div>
        <table class="table table-sm datatable table-condensed table-striped table-hover">
          <thead>
            <tr>
              <th>{{ trans_choice('web::seat.wallet_division_name', 2) }}</th>
              <th>{{ trans_choice('web::seat.balance', 2) }}</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($divisions->sortBy('name') as $division)

            @switch(true)
              @case($division->division == 1 && auth()->user()->has('corporation.wallet_first_division'))
              @case($division->division == 2 && auth()->user()->has('corporation.wallet_second_division'))
              @case($division->division == 3 && auth()->user()->has('corporation.wallet_third_division'))
              @case($division->division == 4 && auth()->user()->has('corporation.wallet_fourth_division'))
              @case($division->division == 5 && auth()->user()->has('corporation.wallet_fifth_division'))
              @case($division->division == 6 && auth()->user()->has('corporation.wallet_sixth_division'))
              @case($division->division == 7 && auth()->user()->has('corporation.wallet_seventh_division'))
                <tr>
                  <td>{{ $division->name }}</td>
                  <td data-order="{{ $division->balance }}">{{ number_format($division->balance) }}</td>
                </tr>
              @break
            @endswitch

          @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <i>Total: {{ number_format($divisions->sum('balance')) }}</i>
    </div>
  </div>

@stop
