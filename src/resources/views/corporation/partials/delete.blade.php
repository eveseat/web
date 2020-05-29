@can('global.superuser')
  <a class="btn btn-danger btn-sm confirmlink" href="{{ route('corporation.delete', ['corporation_id' => $row->corporation_id]) }}">
    {{ trans('web::seat.delete') }}
  </a>
@endcan
