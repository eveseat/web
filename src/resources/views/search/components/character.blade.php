@if(count($characters) > 0)

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Characters Results</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
          <th>{{ trans('web::seat.last_location') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($characters as $character)

          <tr>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $character->characterName }}
              </a>
            </td>
            <td>
              {!! img('corporation', $character->corporationID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $character->corporationName }}
            </td>
            <td>{{ $character->lastKnownLocation }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>


    </div>
  </div>

@endif