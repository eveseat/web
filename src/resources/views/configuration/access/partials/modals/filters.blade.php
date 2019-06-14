<div class="modal fade" role="dialog" id="permission-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">{{ trans('web::permissions.permission_limit') }}</h4>
      </div>
      <div class="modal-body">
        <form>
          <select id="permission-entity-lookup" style="width: 100%;"></select>
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