<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#components-detail" data-url="{{ route('seatcore::tools.moons.show', ['id' => $row->moon_id]) }}">
  <i class="fas fa-eye"></i> {{ trans_choice('web::seat.detail', 1) }}
</button>