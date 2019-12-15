<div class="modal fade in show" tabindex="-1" role="dialog" id="application-read" aria-modal="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <h4 class="modal-title">Application</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

@push('javascript')
  <script>
      $('#application-read').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
              });
      });
  </script>
@endpush