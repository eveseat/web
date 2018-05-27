@if(auth()->user()->has('superuser'))
  <a class="btn btn-danger btn-xs confirmlink" href="{{ route('corporation.delete', ['corporation_id' => $row->corporation_id]) }}">
    {{ trans('web::seat.delete') }}
  </a>
@endif
