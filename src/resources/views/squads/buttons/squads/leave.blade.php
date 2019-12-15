<form method="post" action="{{ route('squads.members.leave', $squad->id) }}">
  {!! csrf_field() !!}
  {!! method_field('DELETE') !!}
  <button type="submit" class="btn btn-danger float-right">
    <i class="fas fa-sign-out-alt"></i> {{ trans('web::squads.leave') }}
  </button>
</form>