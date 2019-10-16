<div class="btn-group float-right">
  <a class="btn btn-primary btn-sm editnote col-xs-6" type="button" data-widget="modal" data-target="#editModal"
     a-note-id="{{ $row->id }}" a-object-id="{{ $row->object_id }}">
    {{ trans('web::seat.edit') }}
  </a>
  <a href="{{ route('character.view.intel.notes.delete', ['character_id' => $row->object_id, 'note_id' => $row->id]) }}"
     type="button" class="btn btn-danger btn-sm confirmlink col-xs-6">
    {{ trans('web::seat.delete') }}
  </a>
</div>
