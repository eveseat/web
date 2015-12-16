@extends('web::character.layouts.view', ['viewname' => 'research'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.research'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.research'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.research') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.start') }}</th>
          <th>{{ trans('web::seat.agent') }}</th>
          <th>{{ trans_choice('web::seat.skill', 1) }}</th>
          <th>{{ trans('web::seat.points_p_day') }}</th>
          <th>{{ trans('web::seat.remainder') }}</th>
        </tr>

        @foreach($agents as $agent)

          <tr>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $agent->researchStartDate }}">
                {{ human_diff($agent->researchStartDate) }}
              </span>
            </td>
            <td>
              {!! img('character', $agent->itemID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $agent->itemName }}
            </td>
            <td>
              {!! img('type', $agent->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $agent->typeName }}
            </td>
            <td>{{ $agent->pointsPerDay }}</td>
            <td>{{ $agent->remainderPoints }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
