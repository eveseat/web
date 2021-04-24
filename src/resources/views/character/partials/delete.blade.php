@can('global.superuser')
  <form method="post" action="{{ route('character.destroy', ['character' => $row->character_id]) }}">
    {{ csrf_field() }}
    {{ method_field('delete') }}
    <button class="btn btn-xs btn-danger confirmdelete" data-seat-entity="{{ trans('web::seat.character') }}">
      <i class="fas fa-trash-alt"></i>
      {{ trans('web::seat.delete') }}
    </button>
  </form>
@endcan
