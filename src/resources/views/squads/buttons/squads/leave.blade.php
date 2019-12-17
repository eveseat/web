<form method="post" action="{{ route('squads.members.leave', $squad->id) }}" id="form-leave">
  {!! csrf_field() !!}
  {!! method_field('DELETE') !!}
</form>
<button type="submit" class="btn btn-danger" form="form-leave">
  <i class="fas fa-sign-out-alt"></i> {{ trans('web::squads.leave') }}
</button>