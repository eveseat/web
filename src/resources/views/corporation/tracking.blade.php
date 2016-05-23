@extends('web::corporation.layouts.view', ['viewname' => 'tracking'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.tracking') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans('web::seat.joined') }}</th>
            <th>{{ trans('web::seat.location') }}</th>
            <th>{{ trans('web::seat.last_login') }}</th>
            <th>{{ trans('web::seat.key') }}</th>
          </tr>
        </thead>
        <tbody>

        @foreach($tracking as $character)

          <tr>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $character->name }}
              </a>
            </td>
            <td data-order="{{ $character->startDateTime }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $character->startDateTime }}">
                {{ human_diff($character->startDateTime) }}
              </span>
            </td>
            <td>
              {{ $character->location }}
              @if($character->shipType != 'Unknown Type')
                <i class="fa fa-cab pull-right" data-toggle="tooltip"
                   title="" data-original-title="{{ $character->shipType }}"></i>
              @endif
            </td>
            <td data-order="{{ $character->logonDateTime }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $character->logonDateTime }}">
                {{ human_diff($character->logonDateTime) }}
              </span>
            </td>
            <td data-order="{{ $character->enabled }}">
              @if($character->enabled)
                <i class="fa fa-check"></i>
              @else
                <i class="fa fa-exclamation-triangle"></i>
              @endif
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
