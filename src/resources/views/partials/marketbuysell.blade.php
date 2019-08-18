@if($row->is_buy_order)
  <span class="text-red">{{ trans('web::market.buy') }}</span>
@else
  <span class="text-green">{{ trans('web::market.sell') }}</span>
@endif