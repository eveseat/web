@if(!is_null($row->refresh_token))
  <button data-toggle="tooltip" title="{{ trans_choice('web::seat.valid_token', 1) }}"
          class="btn btn-xs btn-link">
    <i class="fa fa-check text-success"></i>
  </button>
@else
  <button data-toggle="tooltip" title="{{ trans_choice('web::seat.invalid_token', 1) }}"
          class="btn btn-xs btn-link">
    <i class="fa fa-exclamation-triangle text-danger"></i>
  </button>
@endif