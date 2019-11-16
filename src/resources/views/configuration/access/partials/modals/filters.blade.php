<div class="modal fade" role="dialog" id="permission-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ trans('web::permissions.permission_limit') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <select id="permission-entity-lookup" class="form-control select2" style="width: 100%;"></select>
              </div>
            </div>
          </div>
        </form>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>{{ trans_choice('web::seat.type', 1) }}</th>
              <th>{{ trans_choice('web::seat.name', 1) }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('web::seat.close') }}</button>
      </div>
    </div>
  </div>
</div>