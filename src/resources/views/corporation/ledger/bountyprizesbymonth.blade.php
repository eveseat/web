@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'bountyprizesbymonth', 'breadcrumb' => trans_choice('web::seat.bountyprizesbymonth', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.bountyprizesbymonth', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Available Ledgers</h3>
    </div>
    <div class="panel-body">

      @foreach ($bountyprizes->chunk(3) as $chunk)
        <div class="row">

          @foreach ($chunk as $prize)
            <div class="col-xs-4">
              <span class="text-bold">
                <a href="{{ route('corporation.view.ledger.bountyprizesbymonth', ['corporation_id' => $corporation_id, 'year' => $prize->year, 'month' => $prize->month]) }}">
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
      <h3 class="panel-title">{{ trans_choice('web::seat.bountyprizesbymonth', 2) }}
        - {{ date("M Y", strtotime($year."-".$month."-01")) }}</h3>
    </div>

    <div class="panel-body">
      <div>
        <table class="table datatable table-condensed table-hover table-responsive">
          <thead>
            <tr>
              <th>{{ trans_choice('web::seat.name', 1) }}</th>
              <th>{{ trans_choice('web::seat.bountyprizetotal', 1) }}</th>
            </tr>
          </thead>
          <tbody>

            @foreach ($bountyprizedates as $bpbm)
              <tr>
                <td data-order="{{ $bpbm->second_party_id }}">
                  <a href="{{ route('character.view.sheet', ['character_id' => $bpbm->second_party_id]) }}">
                    {!! img('character', $bpbm->second_party_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                    <span class="id-to-name"
                          data-id="{{ $bpbm->second_party_id }}">{{ trans('web::seat.unknown') }}</span>
                  </a>
                </td>
                <td data-order="{{ $bpbm->total }}">{{ number($bpbm->total) }}</td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="panel-footer">
      <h3 class="panel-title">Total: {{ number($bountyprizedates->sum('total')) }}</h3>
    </div>
  </div>

@stop
