<div class="btn-group pull-right">
  <a href="{{ route('character.view.intel.notes.delete', ['character_id' => $row->character_id, 'note_id' => $row->id]) }}"
     type="button" class="btn btn-primary btn-xs confirmlink col-xs-6">
    {{ trans('web::seat.edit') }}
  </a>
  <a href="{{ route('character.view.intel.notes.delete', ['character_id' => $row->character_id, 'note_id' => $row->id]) }}"
     type="button" class="btn btn-danger btn-xs confirmlink col-xs-6">
    {{ trans('web::seat.delete') }}
  </a>
</div>
