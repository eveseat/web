@foreach($row->characters as $character)
  <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
    {!! img('character', $character->characterID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
    {{ $character->characterName }}
  </a>
@endforeach
