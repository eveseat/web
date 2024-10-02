<button data-toggle="modal" data-target="#filters-modal" class="btn btn-sm btn-warning" id="filters-btn" data-filters="{{ $rules ?: '{}' }}" type="button">
  <i class="fas fa-sliders-h"></i> {{$buttonText ?? trans_choice('web::seat.filter', 1)}}
</button>