@can('moon.manage_moon_reports')
    <button type="submit" class="btn btn-sm btn-danger confirmdelete" data-seat-entity="{{ trans('web::seat.moon') }}" form="moon-delete-{{ $row->moon_id }}">
        <i class="fas fa-trash-alt"></i> {{ trans('web::seat.delete') }}
    </button>
    <form method="post" action="{{ route('tools.moons.destroy', ['report' => $row->moon_id]) }}" id="moon-delete-{{ $row->moon_id }}">
        {!! csrf_field() !!}
        {!! method_field('DELETE') !!}
    </form>
@endcan
