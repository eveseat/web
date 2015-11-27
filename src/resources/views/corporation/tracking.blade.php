@extends('web::corporation.layouts.view', ['viewname' => 'tracking'])

@section('title', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Member Tracking')
@section('page_header', ucfirst(trans_choice('web::corporation.corporation', 1)) . ' Member Tracking')

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Member Tracking</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Name</th>
          <th>Joined</th>
          <th>Location</th>
          <th>Last Login</th>
          <th>Key</th>
        </tr>

        @foreach($tracking as $character)

          <tr>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $character->name }}
              </a>
            </td>
            <td>
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
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $character->logonDateTime }}">
                {{ human_diff($character->logonDateTime) }}
              </span>
            </td>
            <td>
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
