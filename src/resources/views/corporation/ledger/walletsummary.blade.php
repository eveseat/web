@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'summary'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.wallet_divisions', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.wallet_divisions', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.wallet_divisions', 2) }}</h3>
    </div>
    <div class="panel-body">
      <div>
        <table class="table datatable table-condensed table-hover table-responsive">
          <tbody>
            <tr class="active">
              <th>{{ trans_choice('web::seat.wallet_division_name', 2) }}</th>
              <th>{{ trans_choice('web::seat.balance', 2) }}</th>
            </tr>

        	  @foreach ($divisions as $division)

          	  <tr>
                <td>{{ $division->description }}</td>
                <td>{{ number($division->balance) }}</td>
          	  </tr>

            @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="panel-footer">
     <h3 class="panel-title">Total: {{ number($divisions->sum('balance')) }}</h3>
    </div>
  </div>

@stop
