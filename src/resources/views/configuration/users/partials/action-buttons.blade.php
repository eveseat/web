<span class="pull-right">
  @if(auth()->user()->id != $row->id)
    <a href="{{ route('configuration.users.impersonate', ['user_id' => $row->id]) }}"
       title="{{ trans('web::seat.impersonate') }}" class="btn btn-sm btn-default">
    <i class="fa fa-user-secret"></i> {{ trans('web::seat.impersonate') }}
  </a>

  @else
    <a class="btn btn-sm disabled btn-link">
    <i class="fa fa-user"></i> <em class="text-danger">(This is you!)</em>
  </a>
  @endif

  <a href="{{ route('configuration.users.edit', ['user_id' => $row->id]) }}"
     title="{{ trans('web::seat.edit') }}" class="btn btn-sm btn-warning">
  <i class="fa fa-pencil"></i> {{ trans('web::seat.edit') }}
</a>

  @if(auth()->user()->id != $row->id)
    <a href="{{ route('configuration.users.delete', ['user_id' => $row->id]) }}"
       title="{{ trans('web::seat.delete') }}"
       class="confirmlink btn btn-sm btn-danger">
    <i class="fa fa-times"></i> {{ trans('web::seat.delete') }}
  </a>
  @else
    <a href="{{ route('configuration.users.delete', ['user_id' => $row->id]) }}"
       title="{{ trans('web::seat.delete') }}"
       class="confirmlink disabled btn-sm btn-danger btn">
    <i class="fa fa-times"></i> {{ trans('web::seat.delete') }}
  </a>
  @endif
</span>
