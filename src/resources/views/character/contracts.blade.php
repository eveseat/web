@extends('web::character.layouts.view', ['viewname' => 'contracts'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.contracts') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.contracts']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_contracts') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-contracts" data-page-length=50>
        <thead>
        <tr>
          <th>{{ trans('web::seat.created') }}</th>
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
      $('table#character-contracts').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('character.view.contracts.data', ['character_id' => $request->character_id]) }}',
        columns         : [
          {data: 'date_issued', name: 'date_issued', render: human_readable},
          {data: 'issuer_id', name: 'issuer_id'},
          {data: 'type', name: 'type'},
          {
            data: 'status', name: 'status', render: function (data, type, row) {
              var str = data.toLowerCase();
              return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function ($1) {
                return $1.toUpperCase();
              });
            }
          },
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

              // Small hack to get an ajaxable url from Laravel
              var url = "{{ route('character.view.contracts.items', ['character_id' => $request->character_id, 'contract_id' => ':contractid']) }}";
              var contract_id = $(this).attr('a-contract-id');
              url = url.replace(':contractid', contract_id);

              // Perform an ajax request for the contract items
              $.get(url, function (data) {
                $('span#contract-items-result').html(data);
              });

            });

          });
        }
      });
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
