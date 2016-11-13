@if($row->bid)
  {{ $row->volEntered }}
@else
  {{ $row->volRemaining }}/{{ $row->volEntered }}
@endif
