@can('global.superuser')
  <form method="post" action="{{ route('alliance.destroy', ['alliance' => $row->alliance_id]) }}">
    {!! csrf_field() !!}
    {!! method_field('delete') !!}
    <button class="btn btn-xs btn-danger confirmdelete" data-seat-entity="{{ trans_choice('web::seat.alliance', 1) }}">
      <i class="fas fa-trash-alt"></i>
      {{ trans('web::seat.delete') }}
    </button>
@endcan
