@if($row->is_buy_order)
  <span class="text-red">Buy</span>
@else
  <span class="text-green">Sell</span>
@endif