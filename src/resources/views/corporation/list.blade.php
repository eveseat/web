@extends('web::layouts.grids.12')

@section('title', ucfirst(trans_choice('web::corporation.corporation', 2)))
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 2)))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ ucfirst(trans_choice('web::corporation.corporation', 2)) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover">
        <tbody>
        <tr>
          <th>{{ ucfirst(trans_choice('web::general.name', 1)) }}</th>
          <th>{{ trans('web::corporation.ceo_name') }}</th>
          <th>{{ trans('web::corporation.alliance_name') }}</th>
          <th>{{ trans('web::corporation.tax_rate') }}</th>
          <th>{{ trans('web::corporation.member_limit') }}</th>
        </tr>

        @foreach($corporations as $corporation)

          <tr>
            <td>
              {!! img('corporation', $corporation->corporationID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
              {{ $corporation->corporationName }}
            </td>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $corporation->ceoID]) }}">
                {!! img('character', $corporation->ceoID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
                {{ $corporation->ceoName }}
              </a>
            </td>
            <td>
              @if($corporation->allianceID)
                {!! img('alliance', $corporation->allianceID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
                {{ $corporation->allianceName }}
              @endif
            </td>
            <td>{{ $corporation->taxRate }}</td>
            <td>{{ $corporation->memberCount }} / {{ $corporation->memberLimit }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <span class="text-bold">
        {{ count($corporations) }}
      </span>
      {{ ucfirst(trans_choice('web::character.character', count($corporations))) }}
    </div>
  </div>

@stop
