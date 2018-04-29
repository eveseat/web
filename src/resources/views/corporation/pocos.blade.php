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
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.system', 1) }}</th>
          <th>{{ trans('web::seat.reinforcement') }}</th>
          <th>{{ trans('web::seat.alliance') }}</th>
          <th>{{ trans('web::seat.standings') }}</th>
          <th>{{ trans('web::seat.standing_level') }}</th>
          <th colspan="2">{{ trans('web::seat.tax_alliance_corp') }}</th>
          <th colspan="5">{{ trans('web::seat.tax_standings') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pocos as $poco)

          <tr>
            <td>
              @if (is_null($poco->location_id))
                {!! img('type', 0, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $poco->system->itemName }}
              @else
                <span data-toggle="tooltip" title="{{ $poco->planet->type->typeName }}">
                  {!! img('type', $poco->planet->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                </span>
                {{ $poco->planet->itemName }}
              @endif
            </td>
            <td>between {{ $poco->reinforce_exit_start }}h and {{ $poco->reinforce_exit_end }}h</td>
            <td>
              @if($poco->allow_alliance_access)
                {{ trans('web::seat.yes') }}
              @else
                {{ trans('web::seat.no') }}
              @endif
            </td>
            <td>
              @if($poco->allow_access_with_standings)
                {{ trans('web::seat.yes') }}
              @else
                {{ trans('web::seat.no') }}
              @endif
            </td>
            <td>
              @if($poco->standing_level == 'terrible')
                <span class="label label-danger">Terrible</span>
              @elseif($poco->standing_level == 'bad')
                <span class="label label-warning">Bad</span>
              @elseif($poco->standing_level == 'neutral')
                <span class="label label-default">Neutral</span>
              @elseif($poco->standing_level == 'good')
                <span class="label label-info">Good</span>
              @elseif($poco->standing_level == 'excellent')
                <span class="label label-primary">Excellent</span>
              @endif
            </td>
            <td>{{ round((float)$poco->alliance_tax_rate * 100) }}%</td>
            <td>{{ round((float)$poco->corporation_tax_rate * 100) }}%</td>
            <td><span class="text-blue">{{ round((float)$poco->excellent_standing_tax_rate * 100) }}%</span></td>
            <td><span class="text-aqua">{{ round((float)$poco->good_standing_tax_rate * 100) }}%</span></td>
            <td>{{ round((float)$poco->neutral_standing_tax_rate * 100) }}%</td>
            <td><span class="text-warning">{{ round((float)$poco->bad_standing_tax_rate * 100) }}%</span></td>
            <td><span class="text-danger">{{ round((float)$poco->terrible_standing_tax_rate * 100) }}%</span></td>
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
