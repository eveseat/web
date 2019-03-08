<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Mail Results</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="mail">
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

<!-- Mail Content Modal -->
<div class="modal fade" id="mailContentModal" tabindex="-1" role="dialog"
     aria-labelledby="mailContentModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('web::seat.mail') }}</h4>
      </div>
      <div class="modal-body">

        <span id="mail-content-result"></span>

      </div>
    </div>
  </div>
</div>

@push('javascript')

  <script type="text/javascript">

    $('table#mail').DataTable({
      processing  : true,
      serverSide  : true,
      ordering    : false,
      ajax        : '{{ route('support.search.mail.data') }}',
      columns     : [
        {data: 'timestamp', name: 'mail_headers.timestamp', render: human_readable},
        {data: 'from', name: 'from'},
        {data: 'subject', name: 'mail_headers.subject'},
        {data: 'body_clean', name: 'body.body'},
        {data: 'tocounts', name: 'tocounts', searchable: false},
        {data: 'read', name: 'read', searchable: false},
        {data: 'recipients', name: 'recipients', visible: false}
      ],
      drawCallback: function () {

        $('[data-toggle="tooltip"]').tooltip();
        $('img').unveil(100);
        ids_to_names();
        // After loading the mail data, bind a click event
        // on items with the contract-item class.
        $('a.mail-content').on('click', function () {

          // Small hack to get an ajaxable url from Laravel
          var url = $(this).attr('data-url');

          // Perform an ajax request for the contract items
          $.ajax({
            type      : 'GET',
            url       : url,
            beforeSend: function () {
              //add spinner
              $('span#mail-content-result').html('<i class="fa fa-refresh fa-spin loader"></i>');
            },
            success   : function (data) {
              //replace spinner with content
              $('span#mail-content-result').html(data);
              $('img').unveil(100);
              ids_to_names();
            },
            error     : function (xhr) { // if error occured
              alert("Error occured.please try again");
              $(placeholder).append(xhr.statusText + xhr.responseText);
              $(placeholder).removeClass('loading');
            },
          });
        });

      },
      'search'    : {
        'search': '{{ $query }}'
      },
      order       : [[0, "asc"]]
    });


  </script>

@endpush
