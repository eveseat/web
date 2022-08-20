@can('global.superuser')
  <form method="post" action="{{ route('seatcore::character.destroy', ['character' => $row->character_id]) }}">
    {{ csrf_field() }}
    {{ method_field('delete') }}
    <button type="button" class="btn btn-xs btn-danger d-none d-sm-inline-block confirmdelete" data-seat-entity="{{ trans_choice('web::seat.character', 1) }}">
      <i class="fas fa-trash-alt"></i>
      {{ trans('web::seat.delete') }}
    </button>
  </form>
@endcan
