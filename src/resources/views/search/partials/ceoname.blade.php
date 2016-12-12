<a href="{{ route('character.view.sheet', ['character_id' => $row->ceoID]) }}">
  {!! img('character', $row->ceoID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
  {{ $row->ceoName }}
</a>
