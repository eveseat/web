<a href="{{ route('corporation.view.summary', ['corporation_id' => $row->corporation_id]) }}">
  {!! img('corporation', $row->corporation_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
  {{ $row->name }}
</a>
