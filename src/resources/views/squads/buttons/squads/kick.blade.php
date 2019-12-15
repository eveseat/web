<div class="float-right">
    @if($row->id !== auth()->user()->id)
        <button class="btn btn-danger btn-sm" form="form-kick-{{ $row->id }}">
            <i class="fas fa-crosshairs"></i> {{ trans('web::squads.kick') }}
        </button>
        <form method="post" action="{{ route('squads.members.kick', ['id' => request()->id, 'user_id' => $row->id]) }}" id="form-kick-{{ $row->id }}">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
        </form>
    @endif
</div>