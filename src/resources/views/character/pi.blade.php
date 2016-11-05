@extends('web::character.layouts.view', ['viewname' => 'pi'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.pi'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.pi'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.pi') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.updated') }}</th>
          <th>{{ trans('web::seat.system') }}</th>
          <th>{{ trans('web::seat.planet') }}</th>
          <th>{{ trans('web::seat.upgrade_level') }}</th>
          <th>{{ trans('web::seat.no_pins') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($colonies as $colony)

          <tr>
            <td data-order="{{ $colony->lastUpdate }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $colony->lastUpdate }}">
                {{ human_diff($colony->lastUpdate) }}
              </span>
            </td>
            <td>{{ $colony->solarSystemName }}</td>
            <td>
              {!! img('type', $colony->planetTypeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $colony->planetTypeName }}
            </td>
            <td>{{ $colony->upgradeLevel }}</td>
            <td>{{ $colony->numberOfPins }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
