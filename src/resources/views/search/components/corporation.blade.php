@if(count($corporations) > 0)

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Corporations Results</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.ceo', 1) }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($corporations as $corporation)

          <tr>
            <td>
              <a href="{{ route('corporation.view.summary', ['corporation_id' => $corporation->corporationID]) }}">
                {!! img('corporation', $corporation->corporationID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
                {{ $corporation->corporationName }}
              </a>
            </td>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $corporation->ceoID]) }}">
                {!! img('character', $corporation->ceoID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
                {{ $corporation->ceoName }}
              </a>
            </td>
            <td>
              @if($corporation->allianceID)
                {!! img('alliance', $corporation->allianceID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
                {{ $corporation->allianceName }}
              @endif
            </td>
            <td>{{ $corporation->taxRate }}</td>
            <td>{{ $corporation->memberCount }} / {{ $corporation->memberLimit }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>


    </div>
  </div>

@endif