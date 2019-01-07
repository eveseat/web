@extends('web::corporation.layouts.view', ['viewname' => 'contracts', 'breadcrumb' => trans('web::seat.contracts')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contracts'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.contracts') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="corporation-contracts" data-page-length=50>
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.issuer') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.status') }}</th>
          <th>{{ trans_choice('web::seat.title', 1) }}</th>
          <th>{{ trans('web::seat.collateral') }}</th>
          <th>{{ trans('web::seat.price') }}</th>
          <th>{{ trans('web::seat.reward') }}</th>
        </tr>
        </thead>
      </table>

    </div>

  </div>

  <!-- Contracts Items Modal -->
  <div class="modal fade" id="contractsItemsModal" tabindex="-1" role="dialog"
       aria-labelledby="contractsItemsModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">{{ trans('web::seat.contract_items') }}</h4>
        </div>
        <div class="modal-body">

          <span id="contract-items-result"></span>

        </div>
      </div>
    </div>
  </div>

@stop

@push('javascript')

  <script>

    $(function () {
      $('table#corporation-contracts').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('corporation.view.contracts.data', ['corporation_id' => $request->corporation_id]) }}',
        columns         : [
          {data: 'date_issued', name: 'date_issued', render: human_readable},
          {data: 'issuer_id', name: 'issuer_id'},
          {data: 'type', name: 'type'},
          {data: 'status', name: 'status'},
          {data: 'title', name: 'title'},
          {data: 'collateral', name: 'collateral'},
          {data: 'price', name: 'price'},
          {data: 'reward', name: 'reward'},
          {data: 'contents', name: 'contents', searchable: false}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {

            // Load images when they are in the viewport
            $("img").unveil(100);

            // Resolve EVE ids to names.
            ids_to_names();

            // After loading the contracts data, bind a click event
            // on items with the contract-item class.
            $('a.contract-item').on('click', function () {

              var url = $(this).attr('data-url');

              // Perform an ajax request for the contract items
              $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function () {
                  //add spinner
                  $('span#contract-items-result').html('<i class="fa fa-refresh fa-spin loader"></i>');
                },
                success: function (data) {
                  //replace spinner with content
                  $('span#contract-items-result').html(data);
                },
                error: function(xhr) { // if error occured
                  alert("Error occured.please try again");
                  $(placeholder).append(xhr.statusText + xhr.responseText);
                  $(placeholder).removeClass('loading');
                },
              });
            });

          });
        }
      });
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
