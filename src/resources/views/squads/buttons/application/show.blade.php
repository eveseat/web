<button data-toggle="modal" data-target="#application-read" class="btn btn-sm btn-light" data-url="{{ route('squads.application', $row->application_id) }}">
  <i class="fas fa-eye"></i> {{ trans('web::squads.show') }}
</button>