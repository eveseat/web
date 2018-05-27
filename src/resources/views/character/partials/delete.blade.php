@if(auth()->user()->has('superuser'))
  <a class="btn btn-danger btn-xs confirmlink" href="{{ route('character.delete', ['character_id' => $row->character_id]) }}">
    {{ trans('web::seat.delete') }}
  </a>
@endif
