<form method="post" action="{{ route('seatcore::tools.notes.destroy', ['id' => $row->id]) }}">
  {!! csrf_field() !!}
  {!! method_field('DELETE') !!}
  <div class="btn-group btn-group-sm float-right">
    <button type="button" data-toggle="modal" data-target="#note-edit-modal" class="btn btn-sm btn-warning"
            data-url="{{ route('seatcore::tools.notes.update', ['id' => $row->id]) }}">
      <i class="fas fa-pencil-alt"></i>
      {{ trans('web::seat.edit') }}
    </button>
    <button type="submit" class="btn btn-sm btn-danger">
      <i class="fas fa-trash-alt"></i>
      {{ trans('web::seat.delete') }}
    </button>
  </div>
</form>
