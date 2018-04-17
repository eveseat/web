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
            <td data-order="{{ $colony->last_update }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $colony->last_update }}">
                {{ human_diff($colony->last_update) }}
              </span>
            </td>
            <td>{{ $colony->itemName }}</td>
            <td>
              {!! img('type', $colony->typeID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ ucfirst($colony->planet_type) }}
            </td>
            <td>{{ $colony->upgrade_level }}</td>
            <td>{{ $colony->num_pins }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
