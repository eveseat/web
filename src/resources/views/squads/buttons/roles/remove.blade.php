<div class="float-right">
    @if(auth()->user()->hasSuperUser())
        <form method="post" action="{{ route('squads.roles.destroy', request()->id) }}">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <input type="hidden" name="role_id" value="{{ $row->id }}" />
            <button type="submit" class="btn btn-danger btn-sm">
              <i class="fas fa-trash-alt"></i> {{ trans('web::squads.remove') }}
            </button>
        </form>
    @endif
</div>