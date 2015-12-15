@extends('web::character.layouts.view', ['viewname' => 'market'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Market')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Market')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Market</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Issued</th>
          <th>Type</th>
          <th>Vol.</th>
          <th>State</th>
          <th>Price</th>
          <th>Total</th>
          <th>Item Type</th>
        </tr>

        @foreach($orders as $order)

          <tr>
            <td>
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
            <td></td>

          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
