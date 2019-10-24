@if(auth()->user()->hasSuperUser())
  <a class="btn btn-danger btn-sm confirmlink" href="{{ route('corporation.delete', ['corporation_id' => $row->corporation_id]) }}">
    {{ trans('web::seat.delete') }}
  </a>
@endif
