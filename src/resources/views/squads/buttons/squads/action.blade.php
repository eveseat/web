<div class="btn-group float-right">
  <a href="{{ route('squads.show', $row->id) }}" class="btn btn-sm btn-light">
    <i class="fas fa-eye"></i> {{ trans('web::squads.show') }}
  </a>
  @if(auth()->user()->hasSuperUser())
    <button class="btn btn-sm btn-danger" form="form-delete-{{ $row->id }}">
      <i class="fas fa-trash-alt"></i> {{ trans('web::squads.delete') }}
    </button>
    <form method="post" action="{{ route('squads.destroy', $row->id) }}" id="form-delete-{{ $row->id }}">
      {!! csrf_field() !!}
      {!! method_field('DELETE') !!}
    </form>
  @endif
</div>