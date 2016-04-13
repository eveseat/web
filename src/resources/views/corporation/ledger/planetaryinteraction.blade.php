@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'planetaryinteraction'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.planetaryinteraction', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.planetaryinteraction', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Available Ledgers</h3>
    </div>
    <div class="panel-body">

      @foreach ($pidates->chunk(3) as $chunk)
        <div class="row">

          @foreach ($chunk as $prize)
            <div class="col-xs-4">
              <span class="text-bold">
                <a href="{{ route('corporation.view.ledger.planetaryinteraction', ['corporation_id' => $corporation_id, 'year' => $prize->year, 'month' => $prize->month]) }}">
                  {{ date("M Y", strtotime($prize->year."-".$prize->month."-01")) }}
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
      <h3 class="panel-title">{{ trans_choice('web::seat.pi', 2) }} - {{ date("M Y", strtotime($year."-".$month."-01")) }}</h3>
    </div>
    <div class="panel-body">
      <table class="table datatable table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.pitotals', 1) }}</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($pitotals as $pit)

            <tr>
              <td data-order="{{ $pit->ownerName1 }}">
                <a href="{{ route('character.view.sheet', ['character_id' => $pit->ownerID1]) }}">
                  {!! img('character', $pit->ownerID1, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $pit->ownerName1 }}
                </a>
              </td>
              <td data-order="{{ number($pit->total) }}">{{ number($pit->total) }} ISK</td>
            </tr>

          @endforeach

        </tbody>
      </table>
    </div>
  </div>

@stop
