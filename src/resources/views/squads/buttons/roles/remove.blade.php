<div class="float-right">
    @can('squads.manage_roles', request()->squad)
        <form method="post" action="{{ route('squads.roles.destroy', request()->squad) }}">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <input type="hidden" name="role_id" value="{{ $row->id }}" />
            <button type="submit" class="btn btn-danger btn-sm confirmform">
              <i class="fas fa-trash-alt"></i> {{ trans('web::squads.remove') }}
            </button>
        </form>
    @endcan
</div>
