<button class="btn btn-sm btn-danger confirmform" form="form-reject-{{ $row->application_id }}">
  <i class="fas fa-times"></i> {{ trans('web::squads.reject') }}
</button>
<form method="post" action="{{ route('squads.applications.reject', ['squad' => request()->squad, 'id' => $row->application_id]) }}" id="form-reject-{{ $row->application_id }}">
  {!! csrf_field() !!}
  {!! method_field('DELETE') !!}
</form>
