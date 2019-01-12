<div class="modal fade" tabindex="-1" role="dialog" id="moon-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title">Moon probe and analysis report</h3>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('corporation.view.extractions.probe-report') }}" id="moon-report-form">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="moon-report" class="control-label">Report</label>
            <textarea class="form-control" name="moon-report" id="moon-report"></textarea>
            <p class="help-block">
              Use the <code>Copy to Clipboard</code> button from your <code>Moon Probe and Analysis</code> window in order to copy your moon-mining report.
              Then, paste into the textarea in order to retrieve information.
            </p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" form="moon-report-form" class="btn btn-primary">Post report</button>
      </div>
    </div>
  </div>
</div>
