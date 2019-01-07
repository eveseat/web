@extends('web::character.layouts.view', ['viewname' => 'contracts', 'breadcrumb' => trans('web::seat.contracts')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{ trans('web::seat.contracts') }}</a></li>
      <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.contracts') }} </a></li>
      @if(auth()->user()->has('character.jobs'))
        <li class="pull-right">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.contracts']) }}"
             style="color: #000000">
            <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_contracts') }}"></i>
          </a>
        </li>
      @endif
    </ul>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-contracts" data-page-length=50>
        <thead>
        <tr>
          <th>{{ trans('web::seat.created') }}</th>
          <th>{{ trans('web::seat.issuer') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.assignee') }}</th>
          <th>{{ trans('web::seat.acceptor') }}</th>
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
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).data("characters"); // activated tab
      contract_table.draw();
    });
    function allLinkedCharacters() {
      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }


    var contract_table =   $('table#character-contracts').DataTable({

      processing      : true,
      serverSide      : true,
      ajax            : {
        url: '{{ route('character.view.contracts.data', ['character_id' => $request->character_id]) }}',
        data: function ( d ) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns         : [
        {data: 'date_issued', name: 'date_issued', render: human_readable, responsivePriority: 1 },
        {data: 'issuer_id', name: 'issuer_id', responsivePriority: 1 },
        {data: 'type', name: 'type', responsivePriority: 1},
        {data: 'assignee_id', name: 'assignee_id', responsivePriority: 1},
        {data: 'acceptor_id', name: 'acceptor_id', responsivePriority: 1},
        {
          data: 'status', name: 'status', render: function (data, type, row) {
            var str = data.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function ($1) {
              return $1.toUpperCase();
            });
          }
        },
        {data: 'title', name: 'title', responsivePriority: 2},
        {data: 'collateral', name: 'collateral', responsivePriority: 2},
        {data: 'price', name: 'price', responsivePriority: 2},
        {data: 'reward', name: 'reward', responsivePriority: 2},
        {data: 'contents', name: 'contents', searchable: false, responsivePriority: 1 },
      ],
      drawCallback: function () {
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
      },
      createdRow: function (row, data) {
        if (data.is_in_group === true){
          $(row).addClass('info')
        }
      }
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
