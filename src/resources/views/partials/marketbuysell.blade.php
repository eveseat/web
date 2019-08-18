@if($is_buy)
  <span class="text-red">{{ trans('web::market.buy') }}</span>
@else
  <span class="text-green">{{ trans('web::market.sell') }}</span>
@endif