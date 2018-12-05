<a href="{{ route('character.view.sheet', ['character_id' => $row->character_id])}}">
  {!! img('character', $row->character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
  {{ $row->name }}
</a>
