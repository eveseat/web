@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'jumpbridgebymonth'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.jumpbridgebymonth', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.jumpbridgebymonth', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Available Ledgers</h3>
    </div>
    <div class="panel-body">

      @foreach ($jumpbridgedates->chunk(3) as $chunk)
        <div class="row">

          @foreach ($chunk as $fee)
            <div class="col-xs-4">
              <span class="text-bold">
                <a href="{{ route('corporation.view.ledger.jumpbridgebymonth', ['corporation_id' => $corporation_id, 'year' => $fee->year, 'month' => $fee->month]) }}">
                  {{ date("M Y", strtotime($fee->year."-".$fee->month."-01")) }}
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
      <h3 class="panel-title">{{ trans_choice('web::seat.jumpbridgebymonth', 2) }}
        - {{ date("M Y", strtotime($year."-".$month."-01")) }}</h3>
    </div>

    <div class="panel-body">
      <div>
        <table class="table datatable table-condensed table-hover table-responsive">
          <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.jumpbridgetotal', 1) }}</th>
          </tr>
          </thead>
          <tbody>

          @foreach ($jumpbridgedates as $jbpm)
            <tr>
              <td data-order="{{ $jbpm->ownerName1 }}">
                <a href="{{ route('character.view.sheet', ['character_id' => $jbpm->ownerID1]) }}">
                  {!! img('character', $jbpm->ownerID1, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $jbpm->ownerName1 }}
                </a>
              </td>
              <td data-order="{{ number($jbpm->total) }}">{{ number($jbpm->total) }}</td>
            </tr>
          @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="panel-footer">
      <h3 class="panel-title">Total: {{ number($jumpbridgedates->sum('total')) }}</h3>
    </div>
  </div>

@stop
