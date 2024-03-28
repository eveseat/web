<div class="modal fade" tabindex="-1" role="dialog" id="moon-import">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">{{ trans('web::moons.probe_report') }}</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('seatcore::tools.moons.store') }}" id="moon-report-form">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="moon-report" class="control-label">{{ trans('web::moons.report') }}</label>
            <textarea class="form-control" name="moon-report" id="moon-report"></textarea>
            <p class="form-text text-muted mb-0">
              {!! trans('web::moons.probe_report_instruction') !!}
            </p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('web::seat.close') }}</button>
        <button type="submit" form="moon-report-form" class="btn btn-primary">{{ trans('web::moons.post_report') }}</button>
      </div>
    </div>
  </div>
</div>
