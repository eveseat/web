<a href="{{ route('character.view.sheet', ['character_id' => optional($maincharacter)->character_id]) }}">
  {!! img('character', optional($maincharacter)->character_id, 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
  {{ optional($maincharacter)->name }}
</a>