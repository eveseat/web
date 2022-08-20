@if(auth()->user()->id == $row->id)
  <button class="btn btn-sm btn-danger" disabled>
    <i class="fas fa-trash-alt"></i> {{ trans('web::seat.delete') }}
  </button>
@else
  <form method="post" action="{{ route('configuration.users.delete', ['user_id' => $row->id]) }}">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <button class="btn btn-sm btn-danger confirmdelete" data-seat-entity="{{ trans('web::seat.user') }}">
      <i class="fas fa-trash-alt"></i> {{ trans('web::seat.delete') }}
    </button>
  </form>
@endif
