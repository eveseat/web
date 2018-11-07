@if(is_null($row->refresh_token_deleted_at))
  <button data-toggle="tooltip" title="{{ trans('web::seat.valid_token') }}"
          class="btn btn-xs btn-link">
    <i class="fa fa-check text-success"></i>
  </button>
@else
  <button data-toggle="tooltip" title="{{ trans('web::seat.invalid_token') }}"
          class="btn btn-xs btn-link">
    <i class="fa fa-exclamation-triangle text-danger"></i>
  </button>
@endif