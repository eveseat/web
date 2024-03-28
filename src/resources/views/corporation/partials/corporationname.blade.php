<a href="{{ route('seatcore::corporation.view.summary', ['corporation_id' => $row->corporation_id]) }}">
  {!! img('corporations', 'logo', $row->corporation_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
  {{ $row->name }}
</a>
