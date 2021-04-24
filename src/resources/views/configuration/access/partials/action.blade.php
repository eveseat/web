<form method="post" action="{{ route('configuration.access.roles.delete', [$row->id]) }}">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
  <div class="btn-group btn-group-sm float-right">
    <a href="{{ route('configuration.access.roles.edit', [$row->id]) }}" type="button"
       class="btn btn-warning">
      <i class="fas fa-pencil-alt"></i>
      {{ trans('web::seat.edit') }}
    </a>
    @if($row->title !== 'Superuser')
      <button type="submit" class="btn btn-danger confirmdelete" data-seat-entity="{{ trans('web::seat.role') }}">
        <i class="fas fa-trash-alt"></i>
        {{ trans('web::seat.delete') }}
      </button>
    @endif
  </div>
</form>
