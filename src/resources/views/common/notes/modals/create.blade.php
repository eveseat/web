<!-- Create Modal -->
<div class="modal fade" id="note-create-modal" tabindex="-1" role="dialog" aria-labelledby="note-create-modal-label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="note-create-modal-label">Add a Note</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form role="form" action="{{ route('seatcore::tools.notes.store') }}" method="post" id="create-note-form">
          {{ csrf_field() }}

          <input type="hidden" name="object_type" id="create-note-object-type" />
          <input type="hidden" name="object_id" id="create-note-object-id" />

          <div class="form-group row">
            <label for="title" class="col-form-label col-md-2">Title</label>
            <div class="col-md-10">
              <input type="text" name="title" class="form-control" id="title" value="" placeholder="Title...">
            </div>
          </div>

          <div class="form-group">
            <label for="note">Note</label>
            <textarea class="form-control" rows="15" name="note" id="note" placeholder="Note..."></textarea>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-light" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="create-note-form">Add</button>
      </div>
    </div>
  </div>
</div>

@push('javascript')
  <script>
      $('#note-create-modal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);

        $("#create-note-object-type").val(button.data('object-type'));
        $("#create-note-object-id").val(button.data('object-id'));
      });
  </script>
@endpush
