<a href="{{ route('character.view.sheet', ['character_id' => $row->characterID]) }}">
  {!! img('character', $row->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
  {{ $row->characterName }}
</a>
