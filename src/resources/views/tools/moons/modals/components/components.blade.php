<div class="modal fade in" tabindex="-1" role="dialog" id="components-detail">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <h4 class="modal-title">Components</h4>
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
      $('#components-detail').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);

                  $('#rawMaterials').DataTable({
                    "footerCallback": function( row, data, start, end, display) {
                      var api = this.api(), data;
                      // converting to interger to find total
                      var intVal = function ( i ) {
                          return typeof i === 'string' ?
                              i.replace(/[\$,]/g, '')*1 :
                              typeof i === 'number' ?
                                  i : 0;
                      };

                      total = api
                        .column(4)
                        .data()
                        .reduce( function (a, b){
                          return intVal(a) + intVal(b)
                        }, 0);

                      $(api.column(4).footer() ).html(
                        (total).toLocaleString(undefined, {minimumFractionDigits: 2})
                      );
                    }
                  });

                  $('#refinedMaterials').DataTable({
                    "footerCallback": function( row, data, start, end, display) {
                      var api = this.api(), data;
                      // converting to interger to find total
                      var intVal = function ( i ) {
                          return typeof i === 'string' ?
                              i.replace(/[\$,]/g, '')*1 :
                              typeof i === 'number' ?
                                  i : 0;
                      };

                      total = api
                        .column(3)
                        .data()
                        .reduce( function (a, b){
                          return intVal(a) + intVal(b)
                        }, 0);

                      $(api.column(3).footer() ).html(
                        (total).toLocaleString(undefined, {minimumFractionDigits: 2})
                      );
                    }
                  });

                  $('#reactionsCandidates').DataTable();
              });
      });
  </script>
@endpush