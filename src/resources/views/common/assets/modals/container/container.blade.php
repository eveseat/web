<div class="modal fade in" tabindex="-1" role="dialog" id="container-detail">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="overlay dark d-flex justify-content-center align-items-center">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i> Loading...
      </div>
      <div class="modal-header bg-blue">
        <h4 class="modal-title">Container</h4>
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
      $('#container-detail').on('show.bs.modal', function (e) {
          var modal = $(e.target);
          var body = modal.find('.modal-body').empty();
          var overlay = modal.find('.overlay');

          overlay.removeClass('d-none');
          overlay.addClass('d-flex');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
                  overlay.addClass('d-none');
                  overlay.removeClass('d-flex');

                  body.find('table').DataTable();
              });
      });
  </script>
@endpush