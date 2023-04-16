<!-- Create Modal -->
<div class="modal fade" id="note-edit-modal" tabindex="-1" role="dialog" aria-labelledby="note-edit-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="note-edit-modal-label">Note Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form role="form" action="{{ route('seatcore::tools.notes.update', ['id' => ':note_id']) }}" method="post" id="edit-note-form">
          {{ csrf_field() }}
          {{ method_field('PUT') }}

          <!-- note_id and character_id for the update -->
          <input type="hidden" name="note_id" id="update-note-id">

          <div class="form-group row">
            <label for="update-title" class="col-md-2">Title</label>
            <div class="col-md-10">
              <input type="text" name="title" class="form-control" id="update-title" placeholder="Title...">
            </div>
          </div>

          <div class="form-group">
            <label for="update-note">Note</label>
            <textarea class="form-control" rows="15" name="note" id="update-note" placeholder="Note..."></textarea>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-light" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="edit-note-form">Update</button>
      </div>
    </div>
  </div>
</div>

@push('javascript')
<script>

  $('#note-edit-modal').on('show.bs.modal', function (e) {
    var url = $(e.relatedTarget).data('url');

    // Get the full data for the note and populate the modal.
    $.get(url, function (data) {
      // Update the Modal with note metadata.
      $('#edit-note-form').attr('action', url);
      $("#update-title").val(data.title);
      $("#update-note").val(data.note);
    });
  });

</script>
@endpush
