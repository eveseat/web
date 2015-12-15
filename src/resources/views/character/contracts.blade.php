@extends('web::character.layouts.view', ['viewname' => 'contracts'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Contracts')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Contracts')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Contracts</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Created</th>
          <th>Issuer</th>
          <th>Type</th>
          <th>Status</th>
          <th>Title</th>
          <th>Price</th>
          <th>Reward</th>
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
              <span rel="id-to-name">
                {{ $contract->issuerID }}
              </span>
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
