<div class="modal fade" role="dialog" id="member-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ trans('web::permissions.members_addition') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <select id="member-entity-lookup" style="width: 100%;"></select>
        </form>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>{{ trans('web::seat.main_character') }}</th>
              <th>{{ trans('web::seat.related_characters') }}</th>
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