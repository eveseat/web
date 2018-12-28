@extends('web::corporation.ledger.layouts.view', ['sub_viewname' => 'industryfacilitytaxbymonth'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.industryfacilitytaxbymonth', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.industryfacilitytaxbymonth', 2))

@section('ledger_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Available Ledgers</h3>
    </div>
    <div class="panel-body">

      @foreach ($industryfacilitytax->chunk(3) as $chunk)
        <div class="row">

          @foreach ($chunk as $tax)
            <div class="col-xs-4">
              <span class="text-bold">
                <a href="{{ route('corporation.view.ledger.industryfacilitytaxbymonth', ['corporation_id' => $corporation_id, 'year' => $tax->year, 'month' => $tax->month]) }}">
                  {{ date("M Y", strtotime($tax->year."-".$tax->month."-01")) }}
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
      <h3 class="panel-title">{{ trans_choice('web::seat.industryfacilitytaxbymonth', 2) }}
        - {{ date("M Y", strtotime($year."-".$month."-01")) }}</h3>
    </div>

    <div class="panel-body">
      <div>
        <table class="table datatable table-condensed table-hover table-responsive">
          <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans_choice('web::seat.industryfacilitytaxtotal', 1) }}</th>
          </tr>
          </thead>
          <tbody>

          @foreach ($industryfacilitytaxdates as $iftbm)
            <tr>
              <td data-order="{{ $iftbm->ownerName1 }}">
                <a href="{{ route('character.view.sheet', ['character_id' => $iftbm->ownerID1]) }}">
                  {!! img('character', $iftbm->ownerID1, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $iftbm->ownerName1 }}
                </a>
              </td>
              <td data-order="{{ number($iftbm->total) }}">{{ number($iftbm->total) }}</td>
            </tr>
          @endforeach

          </tbody>
        </table>
      </div>
    </div>
    <div class="panel-footer">
      <h3 class="panel-title">Total: {{ number($industryfacilitytaxdates->sum('total')) }}</h3>
    </div>
  </div>

@stop
