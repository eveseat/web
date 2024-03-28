<div class="modal fade in show" tabindex="-1" role="dialog" id="application-create" aria-modal="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h4 class="modal-title">Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('seatcore::squads.applications.store', $squad) }}" id="application-form">
          {!! csrf_field() !!}
          <div class="form-group">
            <label for="message" class="col-form-label">{{ trans('web::squads.message') }}</label>
            <textarea class="form-control" id="message" name="message"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" form="application-form" class="btn btn-primary">Send</button>
      </div>
    </div>
  </div>
</div>