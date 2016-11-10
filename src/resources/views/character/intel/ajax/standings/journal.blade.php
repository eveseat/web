<table class="table table-condensed table-hover table-responsive">
  <tbody>
  <tr>
    <th>Interactions</th>
    <th>Character Name</th>
    <th>Character Corp</th>
    <th>Character Alliance</th>
    <th>Standing</th>
  </tr>

  @foreach($journal as $entry)

    <tr>
      <td>{{ $entry->total }}</td>
      <td>
        {!! img('character', $entry->affiliation_character_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
        {{ $entry->affiliation_character_name }}
      </td>
      <td>
        {!! img('corporation', $entry->affiliation_corporation_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
        {{ $entry->affiliation_corporation_name }}
      </td>
      <td>
        {!! img('alliance', $entry->affiliation_alliance_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
        {{ $entry->affiliation_alliance_name }}
      </td>
      <td>{{ $entry->standing }}</td>
    </tr>

  @endforeach

  </tbody>
</table>
