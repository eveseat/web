@extends('web::corporation.layouts.view', ['viewname' => 'contracts'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contracts'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contracts'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.contracts') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.issuer') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.status') }}</th>
          <th>{{ trans_choice('web::seat.title', 1) }}</th>
          <th>{{ trans('web::seat.price') }}</th>
          <th>{{ trans('web::seat.reward') }}</th>
        </tr>

        @foreach($contracts as $contract)

          <tr>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $contract->dateIssued }}">
                {{ human_diff($contract->dateIssued) }}
              </span>
            </td>
            <td>
              {!! img('auto', $contract->issuerID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span rel="id-to-name">{{ $contract->issuerID }}</span>
            </td>
            <td>
              <i class="fa @if($contract->type == 'ItemExchange') fa-exchange @else fa-truck @endif"
                 data-toggle="tooltip"
                 title="" data-original-title="{{ $contract->type }}">
              </i>
              <i class="fa fa-long-arrow-right"></i>
              <i class="fa fa-map-marker" data-toggle="tooltip"
                 title="" data-original-title="{{ $contract->endlocation }}"></i>
              </span>
            </td>
            <td>{{ $contract->status }}</td>
            <td>{{ $contract->title }}</td>
            <td>{{ number($contract->price) }}</td>
            <td>{{ number($contract->reward) }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop

@section('javascript')

  @include('web::includes.javascript.id-to-name')

@stop
