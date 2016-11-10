<table class="table table-condensed table-hover table-responsive">
  <tbody>
  <tr>
    <th>Total</th>
    <th>Type</th>
    <th>Character Name</th>
    <th>Character Corp</th>
    <th>Character Alliance</th>
  </tr>

  @foreach($top as $entry)

    <tr>
      <td>{{ $entry->total }}</td>
      <td>{{ $entry->refTypeName }}</td>
      <td>
        {!! img('character', $entry->characterID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
        {{ $entry->characterName }}
      </td>
      <td>
        {!! img('corporation', $entry->corporationID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
        {{ $entry->corporationName }}
      </td>
      <td>
        {!! img('alliance', $entry->allianceID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
        {{ $entry->allianceName }}
      </td>
    </tr>

  @endforeach

  </tbody>
</table>
