<div class="modal fade in" tabindex="-1" role="dialog" id="killmail-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Killmail</h4>
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
    $('#killmail-detail').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
                ids_to_names();
                $('body').find('#killmail-attackers').dataTable({
                    lengthMenu: [5, 10, 15],
                    ordering:  false,
                    pageLength: 5
                });
                $('body').find('#killmail-items').dataTable({
                    lengthMenu: [5, 10, 15],
                    pageLength: 5
                });
            });
    });
</script>
@endpush