<div class="card">
  <div class="card-header">
    <h3 class="card-title">Mail Results</h3>
  </div>
  <div class="card-body">
    <table class="table table-condensed table-striped table-hover" id="mail">
      <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.from') }}</th>
          <th>{{ trans_choice('web::seat.title', 1) }}</th>
          <th>{{ trans('content') }}</th>
          <th>{{ trans('web::seat.to') }}</th>
          <th></th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@include('web::common.mails.modals.read.read')

@push('javascript')

  <script type="text/javascript">
    $('table#mail').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : '{{ route('seatcore::support.search.mail.data') }}',
      columns     : [
        {data: 'timestamp', name: 'timestamp'},
        {data: 'sender.name', name: 'sender.name'},
        {data: 'subject', name: 'subject'},
        {data: 'body.body', name: 'body.body'},
        {data: 'tocounts', name: 'tocounts', searchable: false, sortable: false},
        {data: 'read', name: 'read', searchable: false, sortable: false},
        {data: 'recipients', name: 'recipients', visible: false}
      ],
      drawCallback: function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('img').unveil(100);
        ids_to_names();
      },
      'search'    : {
        'search': '{{ $query }}'
      },
      order       : [[0, "desc"]]
    });

    $('#mail-content').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
                ids_to_names();
            });
    });
  </script>

@endpush
