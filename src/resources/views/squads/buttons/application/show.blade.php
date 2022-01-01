<button data-toggle="modal" data-target="#application-read" class="btn btn-sm btn-light" data-url="{{ route('seatcore::squads.applications.show', ['squad' => request()->squad, 'id' => $row->application_id]) }}">
  <i class="fas fa-eye"></i> {{ trans('web::squads.show') }}
</button>
