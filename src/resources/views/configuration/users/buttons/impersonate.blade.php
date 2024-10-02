@if(auth()->user()->id == $row->id)
  <button class="btn btn-sm btn-link" disabled>
    <i class="fas fa-user"></i> <em class="text-danger">(This is you!)</em>
  </button>
@else
  <form method="post" action="{{ route('seatcore::configuration.users.impersonate', ['user_id' => $row->id]) }}">
    {{ csrf_field() }}
    <button type="submit" class="btn btn-sm btn-default">
      <i class="fas fa-user-secret"></i> {{ trans('web::seat.impersonate') }}
    </button>
  </form>
@endif
