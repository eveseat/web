<a href="{{ route('seatcore::character.view.default', ['character' => $character]) }}"
   data-toggle="tooltip" title="{{ $character->name }}">
  {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
</a>
