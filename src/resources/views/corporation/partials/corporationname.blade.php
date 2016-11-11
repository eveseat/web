<a href="{{ route('corporation.view.summary', ['corporation_id' => $row->corporationID]) }}">
  {!! img('corporation', $row->corporationID, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
  {{ $row->corporationName }}
</a>
