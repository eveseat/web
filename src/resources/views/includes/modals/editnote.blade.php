<!-- Create Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="createModalLabel">
          Note Details
        </h4>
      </div>
      <div class="modal-body">

        <form role="form" action="{{ $post_route }}" method="post">
        {{ csrf_field() }}

        <!-- note_id and character_id for the update -->
          <input type="hidden" name="note_id" id="update-note-id">
          <input type="hidden" name="object_id" id="update-object-id">

          <div class="box-body">

            <div class="form-group">
              <label for="text">Title</label>
              <input type="text" name="title" class="form-control" id="update-title" value=""
                     placeholder="Title...">
            </div>

            <div class="form-group">
              <label>Note</label>
              <textarea class="form-control" rows="15" name="note" id="update-note"
                        placeholder="Note..."></textarea>
            </div>

          </div><!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">
              Update
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

@push('javascript')

<script>

  $(document).on("click", "a.editnote", function () {

    // Extract the note and object_ids
    var note_id = $(this).attr('a-note-id');
    var object_id = $(this).attr('a-object-id');

    // Update the modal with the note and object ids
    $("input#update-note-id").val(note_id);
    $("input#update-object-id").val(object_id);

    // Shitty hack so we can replace the ids. Muhaha.
    var url = "{{ route('character.view.intel.notes.single.data', ['character_id' => ':object_id', 'profile_id' => ':note_id']) }}";
    url = url.replace(':note_id', note_id);
    url = url.replace(':object_id', object_id);

    // Get the full data for the note and opulate the modal.
    $.get(url, function (data) {

      // Update the Modal with the title and note.
      $("input#update-title").val(data.title);
      $("textarea#update-note").val(data.note);

    })
  });

</script>
@endpush
