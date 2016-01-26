@extends('web::character.layouts.view', ['viewname' => 'contracts'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.contracts') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans('web::seat.created') }}</th>
            <th>{{ trans('web::seat.issuer') }}</th>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
            <th>{{ trans('web::seat.status') }}</th>
            <th>{{ trans_choice('web::seat.title', 1) }}</th>
            <th>{{ trans('web::seat.price') }}</th>
            <th>{{ trans('web::seat.reward') }}</th>
          </tr>
        </thead>
        <tbody>

        @foreach($contracts as $contract)

          <tr>
            <td data-order="{{ $contract->dateIssued }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $contract->dateIssued }}">
                {{ human_diff($contract->dateIssued) }}
              </span>
            </td>
            <td>
              {!! img('auto', $contract->issuerID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span rel="id-to-name">{{ $contract->issuerID }}</span>
            </td>
            <td data-search="{{ $contract->type }}" data-order="{{ $contract->type }}">
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
            <td data-order="{{ $contract->price }}">{{ number($contract->price) }}</td>
            <td data-order="{{ $contract->reward }}">{{ number($contract->reward) }}</td>
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
