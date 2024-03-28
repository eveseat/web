<button class="btn btn-sm btn-success" form="form-approve-{{ $row->application_id }}">
  <i class="fas fa-check"></i> {{ trans('web::squads.approve') }}
</button>
<form method="POST" action="{{ route('seatcore::squads.applications.approve', ['squad' => request()->squad, 'id' => $row->application_id]) }}" id="form-approve-{{ $row->application_id }}">
  {!! csrf_field() !!}
</form>
