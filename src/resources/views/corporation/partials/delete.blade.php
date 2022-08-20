@can('global.superuser')
  <form method="post" action="{{ route('corporation.destroy', ['corporation' => $row->corporation_id]) }}">
    {!! csrf_field() !!}
    {!! method_field('delete') !!}
    <button class="btn btn-xs btn-danger confirmdelete" data-seat-entity="{{ trans('web::seat.corporation') }}">
      <i class="fas fa-trash-alt"></i>
      {{ trans('web::seat.delete') }}
    </button>
@endcan
