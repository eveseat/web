@extends('web::character.layouts.view', ['viewname' => 'market'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.market'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.market'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.market') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans('web::seat.date') }}</th>
            <th></th>
            <th>{{ trans('web::seat.volume') }}</th>
            <th>{{ trans('web::seat.status') }}</th>
            <th>{{ trans('web::seat.price') }}</th>
            <th>{{ trans('web::seat.total') }}</th>
            <th>{{ trans_choice('web::seat.type', 1) }}</th>
          </tr>
        </thead>
        <tbody>

        @foreach($orders as $order)

          <tr>
            <td data-order="{{ $order->issued }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $order->issued }}">
                {{ human_diff($order->issued) }}
              </span>
            </td>
            <td>
              @if($order->bid)
                <span class="text-red">Buy</span>
              @else
                <span class="text-green">Sell</span>
              @endif
            </td>
            <td>
              @if($order->bid)
                {{ $order->volEntered }}
              @else
                {{ $order->volRemaining }}/{{ $order->volEntered }}
              @endif
            </td>
            <td>{{ $states[$order->orderState] }}</td>
            <td>{{ number($order->price) }}</td>
            <td>{{ number($order->price * $order->volEntered) }}</td>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $order->stationName }}">
                <i class="fa fa-map-marker"></i>
              </span>

              {!! img('type', $order->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $order->typeName }}
            </td>

          </tr>

        @endforeach

        </tbody>
      </table>

    </div>

    @if($orders->render())
      <div class="panel-footer">
        {!! $orders->render() !!}
      </div>
    @endif

  </div>

@stop
