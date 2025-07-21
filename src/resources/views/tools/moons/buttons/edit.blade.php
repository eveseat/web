<button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#moon-import" data-url="{{ route('seatcore::tools.moons.edit', $row->moon_id) }}">
  <i class="fas fa-pen"></i> {{ trans_choice('web::seat.edit', 1) }}
</button>