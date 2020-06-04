@can('global.superuser')
  <a class="btn btn-danger btn-sm confirmlink" href="{{ route('character.delete', ['character' => $row->character_id]) }}">
    {{ trans('web::seat.delete') }}
  </a>
@endcan
