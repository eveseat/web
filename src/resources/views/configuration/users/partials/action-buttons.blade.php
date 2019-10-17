<div class="btn-group btn-group-sm float-right">
  @if(auth()->user()->id != $row->id)
    <a href="{{ route('configuration.users.impersonate', ['user_id' => $row->id]) }}"
       title="{{ trans('web::seat.impersonate') }}" class="btn btn-sm btn-default">
      <i class="fas fa-user-secret"></i> {{ trans('web::seat.impersonate') }}
    </a>
  @else
    <a class="btn btn-sm disabled btn-link">
      <i class="fas fa-user"></i> <em class="text-danger">(This is you!)</em>
    </a>
  @endif

  <a href="{{ route('configuration.users.edit', ['user_id' => $row->id]) }}"
     title="{{ trans('web::seat.edit') }}" class="btn btn-sm btn-warning">
    <i class="fas fa-pencil-alt"></i> {{ trans('web::seat.edit') }}
  </a>

  @if(auth()->user()->id != $row->id)
    <a href="{{ route('configuration.users.delete', ['user_id' => $row->id]) }}"
       title="{{ trans('web::seat.delete') }}"
       class="confirmlink btn btn-sm btn-danger">
      <i class="fas fa-trash-alt"></i> {{ trans('web::seat.delete') }}
    </a>
  @else
    <a href="{{ route('configuration.users.delete', ['user_id' => $row->id]) }}"
       title="{{ trans('web::seat.delete') }}"
       class="confirmlink disabled btn-sm btn-danger btn">
      <i class="fas fa-trash-alt"></i> {{ trans('web::seat.delete') }}
    </a>
  @endif
</div>
