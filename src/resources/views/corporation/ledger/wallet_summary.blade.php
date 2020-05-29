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
              @case($division->division == 1 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_first_division', $corporation))
              @case($division->division == 2 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_second_division', $corporation))
              @case($division->division == 3 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_third_division', $corporation))
              @case($division->division == 4 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_fourth_division', $corporation))
              @case($division->division == 5 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_fifth_division', $corporation))
              @case($division->division == 6 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_sixth_division', $corporation))
              @case($division->division == 7 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_seventh_division', $corporation))
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
