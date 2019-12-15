@if($value)
  <span class="text text-success">
      <i class="fas fa-check"></i> {{ trans('web::seat.yes') }}
  </span>
@else
  <span class="text text-danger">
      <i class="fas fa-times"></i> {{ trans('web::seat.no') }}
  </span>
@endif