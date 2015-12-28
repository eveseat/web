@extends('web::corporation.layouts.view', ['viewname' => 'pocos'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.pocos'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.pocos'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.pocos') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.reinforcement') }}</th>
          <th>{{ trans('web::seat.alliance') }}</th>
          <th>{{ trans('web::seat.standings') }}</th>
          <th>{{ trans('web::seat.standing_level') }}</th>
          <th colspan="2">{{ trans('web::seat.tax_alliance_corp') }}</th>
          <th colspan="5">{{ trans('web::seat.tax_standings') }}</th>
        </tr>
        @foreach($pocos as $poco)

          <tr>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $poco->planetTypeName }}">
                {!! img('type', $poco->planetTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              </span>
              {{ $poco->planetName }}
            </td>
            <td>{{ $poco->reinforceHour }}</td>
            <td>
              @if($poco->allowAlliance)
                {{ trans('web::seat.yes') }}
              @else
                {{ trans('web::seat.no') }}
              @endif
            </td>
            <td>
              @if($poco->allowStandings)
                {{ trans('web::seat.yes') }}
              @else
                {{ trans('web::seat.no') }}
              @endif
            </td>
            <td>
              <span class="
                    @if($poco->standingLevel >= 0.5)
                      text-green
                    @elseif($poco->standingLevel < 0.5 && $poco->standingLevel > 0.0)
                      text-warning
                    @else
                      text-red
                    @endif">
                {{ round($poco->standingLevel, 2) }}
            </td>
            <td>{{ round((float)$poco->taxRateAlliance * 100) }}%</td>
            <td>{{ round((float)$poco->taxRateCorp * 100) }}%</td>
            <td><span class="text-blue">{{ round((float)$poco->taxRateStandingHigh * 100) }}%</span></td>
            <td><span class="text-aqua">{{ round((float)$poco->taxRateStandingGood * 100) }}%</span></td>
            <td>{{ round((float)$poco->taxRateStandingNeutral * 100) }}%</td>
            <td><span class="text-warning">{{ round((float)$poco->taxRateStandingBad * 100) }}%</span></td>
            <td><span class="text-danger">{{ round((float)$poco->taxRateStandingHorrible * 100) }}%</span></td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($pocos) }} {{ trans('web::seat.pocos') }}
    </div>
  </div>

@stop
