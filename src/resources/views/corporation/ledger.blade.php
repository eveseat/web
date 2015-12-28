@extends('web::corporation.layouts.view', ['viewname' => 'ledger'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_ledger'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_ledger'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.wallet_ledger') }}</h3>
    </div>
    <div class="panel-body">
        
      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th> {{ trans('web::seat.year') }} </th>
          <th> {{ trans('web::seat.month') }} </th>
          <th> {{ trans('web::seat.account_key') }} </th>
          <th> {{ trans('web::seat.wallet_balance') }} </th>
        </tr>
        
        @foreach($ledger as $entry)
          <tr>
            <td> {{ $entry->year }} </td>
            <td> {{ $entry->month }} </td>
            <td> {{ $entry->accountKey }} </td>
            <td> {{ number($entry->balance) }} </td>
          </tr>
        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
