<a href="{{ route('character.view.sheet', ['character_id' => $row->ceo_id]) }}">
  {!! img('character', $row->ceo_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
   <span rel="id-to-name">{{ $row->ceo_id }}</span>
</a>
