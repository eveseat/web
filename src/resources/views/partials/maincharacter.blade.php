@if(is_null(optional($maincharacter)->character_id))

  Unknown

@else

  <a href="{{ route('seatcore::character.view.sheet', ['character' => $maincharacter]) }}">
    {!! img('characters', 'portrait', $maincharacter->character_id, 64, ['class' => 'img-circle eve-icon small-icon'],false) !!}
    {{ $maincharacter->name }}
  </a>

@endif