<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="createModalLabel">
          Add a Note
        </h4>
      </div>
      <div class="modal-body">

        <form role="form" action="{{ $post_route }}" method="post">
          {{ csrf_field() }}

          <div class="box-body">

            <div class="form-group">
              <label for="text">Title</label>
              <input type="text" name="title" class="form-control" id="title" value=""
                     placeholder="Title...">
            </div>

            <div class="form-group">
              <label>Note</label>
              <textarea class="form-control" rows="15" name="note" placeholder="Note..."></textarea>
            </div>

          </div><!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">
              Add
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>