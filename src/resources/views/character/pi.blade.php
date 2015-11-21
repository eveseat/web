@extends('web::character.layouts.view', ['viewname' => 'pi'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Planetary Interaction')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Planetary Interaction')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Planetary Interaction</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Updated</th>
          <th>System</th>
          <th>Planet</th>
          <th>Upgrade Lvl.</th>
          <th># Pins</th>
        </tr>

        @foreach($colonies as $colony)

          <tr>
            <td>
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
