<div class="text-right">
    @can('squads.kick', [request()->squad, $row])
        <form method="post" action="{{ route('squads.members.kick', ['squad' => request()->squad, 'user' => $row]) }}">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-crosshairs"></i> {{ trans('web::squads.kick') }}
            </button>
        </form>
    @endcan
</div>