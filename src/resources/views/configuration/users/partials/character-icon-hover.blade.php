<a href="{{ route('character.view.default', ['character_id' => $character->character_id]) }}"
   data-toggle="tooltip" title="{{ $character->name }}">
  {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
</a>
