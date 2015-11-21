@extends('web::character.layouts.view', ['viewname' => 'research'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Research')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Research')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Research</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Start</th>
          <th>Agent</th>
          <th>Skill</th>
          <th>Points p/day</th>
          <th>Remainder</th>
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
