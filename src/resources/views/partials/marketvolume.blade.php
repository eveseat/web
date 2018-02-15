@if($row->is_buy_order)
  {{ $row->volume_total }}
@else
  {{ $row->volume_remaining }}/{{ $row->volume_total }}
@endif
