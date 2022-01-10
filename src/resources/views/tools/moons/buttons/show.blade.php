<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#components-detail" data-url="{{ route('seatcore::tools.moons.show', ['id' => $row->moon_id]) }}">
  <i class="fas fa-eye"></i> {{ trans_choice('web::seat.detail', 1) }}
</button>