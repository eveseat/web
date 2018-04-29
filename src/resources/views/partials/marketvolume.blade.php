@if($row->is_buy_order)
  {{ $row->volume_total }}
@else
  {{ $row->volume_remain }} / {{ $row->volume_total }}
@endif
