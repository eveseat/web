@if(!is_null($row->refresh_token))
  <button data-toggle="tooltip" title="{{ trans('web::seat.valid_token') }}"
          class="btn btn-xs btn-link">
    <i class="fas fa-check text-success"></i>
  </button>
@else
  <button data-toggle="tooltip" title="{{ trans('web::seat.invalid_token') }}"
          class="btn btn-xs btn-link">
    <i class="fas fa-exclamation-triangle text-danger"></i>
  </button>
@endif