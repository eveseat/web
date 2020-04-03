<div class="text-right">
    @if($row->id !== auth()->user()->id && ($squad->is_moderator || auth()->user()->hasSuperUser()))
        <form method="post" action="{{ route('squads.members.kick', request()->id) }}">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <input type="hidden" name="user_id" value="{{ $row->id }}" />
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-crosshairs"></i> {{ trans('web::squads.kick') }}
            </button>
        </form>
    @endif
</div>