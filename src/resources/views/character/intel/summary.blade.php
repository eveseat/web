@extends('web::character.intel.layouts.view', ['sub_viewname' => 'summary', 'breadcrumb' => trans('web::seat.intel')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab"
                            data-characters="single">{{trans_choice('web::seat.character', 1)}}</a></li>
      <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }}</a></li>
    </ul>

    <div class="tab-content">
      <h3>Top Wallet Journal Interactions</h3>

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-top-journal-interactions" data-page-length=10>
        <thead>
        <tr>
          <th>Total</th>
          <th>Type</th>
          <th>Character Name</th>
          <th>Character Corp</th>
          <th>Character Alliance</th>
        </tr>
        </thead>
      </table>

      <!-- Journal Content Modal -->
      <div class="modal fade" id="journalContentModal" tabindex="-1" role="dialog"
           aria-labelledby="journalContentModalLabel">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="journalContentModalLabel">{{ trans('web::seat.wallet_journal') }}</h4>
            </div>
            <div class="modal-body">
              <table class="table compact table-condensed table-hover table-responsive"
                     id="character-journal" data-page-length=100>
                <thead>
                <tr>
                  <th>{{ trans('web::seat.date') }}</th>
                  <th>{{ trans_choice('web::seat.type', 1) }}</th>
                  <th>{{ trans('web::seat.owner_1') }}</th>
                  <th>{{ trans('web::seat.owner_2') }}</th>
                  <th>{{ trans('web::seat.amount') }}</th>
                  <th>{{ trans('web::seat.balance') }}</th>
                </tr>
                </thead>
              </table>

            </div>
          </div>
        </div>
      </div>

      <hr>

      <h3>Top Wallet Transaction Interactions</h3>

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-top-transaction-interactions" data-page-length=10>
        <thead>
        <tr>
          <th>Total</th>
          <th>Character Name</th>
          <th>Character Corp</th>
          <th>Character Alliance</th>
        </tr>
        </thead>
      </table>

      <!-- Transaction Content Modal -->
      <div class="modal fade" id="transactionContentModal" tabindex="-1" role="dialog"
           aria-labelledby="transactionContentModalLabel">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title"
                  id="transactionContentModalLabel">{{ trans('web::seat.wallet_transactions') }}</h4>
            </div>
            <div class="modal-body">

              <table class="table compact table-condensed table-hover table-responsive"
                     id="character-transactions">
                <thead>
                <tr>
                  <th>{{ trans('web::seat.date') }}</th>
                  <th></th>
                  <th>{{ trans_choice('web::seat.type', 1) }}</th>
                  <th>{{ trans('web::seat.qty') }}</th>
                  <th>{{ trans('web::seat.price') }}</th>
                  <th>{{ trans('web::seat.total') }}</th>
                  <th>{{ trans('web::seat.client') }}</th>
                </tr>
                </thead>
              </table>
            </div>
          </div>

        </div>
      </div>


      <hr>

      <h3>Top Mail Interactions</h3>

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-top-mail-interactions" data-page-length=10>
        <thead>
        <tr>
          <th>Total</th>
          <th>Character Name</th>
          <th>Character Corp</th>
          <th>Character Alliance</th>
        </tr>
        </thead>
      </table>

      <!-- Top Mail Content Modal -->
      <div class="modal fade" id="topMailContentModal" tabindex="-1" role="dialog"
           aria-labelledby="topMailContentModalLabel">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="topMailContentModalLabel">{{ trans('web::seat.mail') }}</h4>
            </div>
            <div class="modal-body">

              <table class="table compact table-condensed table-hover table-responsive"
                     id="character-mail" data-page-length=50>
                <thead>
                <tr>
                  <th>{{ trans('web::seat.date') }}</th>
                  <th>{{ trans('web::seat.from') }}</th>
                  <th>{{ trans_choice('web::seat.title', 1) }}</th>
                  <th data-orderable="false">{{ trans('web::seat.to') }}</th>
                  <th data-orderable="false"></th>
                </tr>
                </thead>
              </table>
              <div class="panel-footer clearfix">
                <div class="col-md-2 col-md-offset-2">
                  <span class="label label-warning">0</span> Corporation
                </div>
                <div class="col-md-2">
                  <span class="label label-primary">0</span> Alliance
                </div>
                <div class="col-md-2">
                  <span class="label label-info">0</span> Characters
                </div>
                <div class="col-md-2">
                  <span class="label label-success">0</span> Mailing-Lists
                </div>
              </div>
            </div>
          </div>

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
            <div class="modal-footer">
              <a href="#" class="btn btn-default pull-left" data-bm-open="#topMailContentModal"
                 data-bm-close="#mailContentModal">{{ trans('web::seat.back') }}</a>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

@stop

@push('javascript')

  <script>

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
      characterTopJournalTable.draw();
      characterTopTransactionTable.draw();
      characterTopMailTable.draw();
    });

    function allLinkedCharacters() {

      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }

    var characterTopJournalTable = $('table#character-top-journal-interactions').DataTable({
      processing  : true,
      serverSide  : true,
      searching   : false,
      ordering    : false,
      ajax        : {
        url : '{{ route('character.view.intel.summary.journal.data', ['character_id' => $request->character_id]) }}',
        data: function (d) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns     : [
        {data: 'total', name: 'total', searchable: false},
        {data: 'ref_type', name: 'ref_type'},
        {data: 'character', name: 'first_party.name'},
        {data: 'corporation', name: 'corporation_id'},
        {data: 'alliance', name: 'alliance_id'}
      ],
      createdRow: function (row, data) {
        if (data.is_in_group === '1'){
          $(row).addClass('info')
        }
      },
      drawCallback: function () {

        $('img').unveil(100);
        ids_to_names();

        // After loading the journal data, bind a click event
        // on items with the journal-contet class.
        $('a.journal-content').on('click', function () {

          var url = $(this).attr('data-url');

          var characterJournal = $('table#character-journal').DataTable({
            processing  : true,
            serverSide  : true,
            destroy     : true,
            ajax        : {
              url: url
            },
            columns     : [
              {data: 'date', name: 'date', render: human_readable, type: 'date'},
              {data: 'ref_type', name: 'ref_type'},
              {data: 'first_party_id', name: 'first_party.name'},
              {data: 'second_party_id', name: 'second_party.name'},
              {data: 'amount', name: 'amount'},
              {data: 'balance', name: 'balance'},
              {data: 'reason', name: 'reason', visible: false}
            ],
            drawCallback: function () {
              $("[data-toggle=tooltip]").tooltip();
              $('img').unveil(100);
              ids_to_names();
            }
          });
        });

      }
    });

    var characterTopTransactionTable = $('table#character-top-transaction-interactions').DataTable({
      processing  : true,
      serverSide  : true,
      searching   : false,
      ordering    : false,
      ajax        : {
        url : '{{ route('character.view.intel.summary.transactions.data', ['character_id' => $request->character_id]) }}',
        data: function (d) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns     : [
        {data: 'total', name: 'total', searchable: false},
        {data: 'character', name: 'character'},
        {data: 'corporation', name: 'corporation'},
        {data: 'alliance', name: 'alliance'}
      ],
      drawCallback: function () {

        $('img').unveil(100);
        ids_to_names();

        // After loading the transaction data, bind a click event
        // on items with the transaction-contet class.
        $('a.transaction-content').on('click', function () {

          var url = $(this).attr('data-url');

          var character_transactions = $('table#character-transactions').DataTable({
            processing  : true,
            serverSide  : true,
            destroy     : true,
            ajax        : {
              url: url
            },
            columns     : [
              {data: 'date', name: 'date', render: human_readable},
              {data: 'is_buy', searchable: false},
              {data: 'item_view', name: 'type.typeName'},
              {data: 'quantity', name: 'quantity'},
              {data: 'unit_price', name: 'unit_price'},
              {data: 'total', name: 'unit_price'},
              {data: 'client_view', name: 'client.name'}
            ],
            drawCallback: function () {
              $('img').unveil(100);
              ids_to_names();
              $('[data-toggle="tooltip"]').tooltip();
            }
          });
        });
      }
    });

    var characterTopMailTable = $('table#character-top-mail-interactions').DataTable({
      processing  : true,
      serverSide  : true,
      searching   : false,
      ordering    : false,
      ajax        : {
        url : '{{ route('character.view.intel.summary.mail.data', ['character_id' => $request->character_id]) }}',
        data: function (d) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns     : [
        {data: 'total', name: 'total', searchable: false},
        {data: 'character_id', name: 'character_id'},
        {data: 'corporation_id', name: 'corporation_id'},
        {data: 'alliance_id', name: 'alliance_id'}
      ],
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();

        // After loading the mail data, bind a click event
        // on items with the top-mail-content class.
        $('a.top-mail-content').on('click', function () {

          var url = $(this).attr('data-url');

          var characterTopMail = $('table#character-mail').DataTable({
            processing  : true,
            serverSide  : true,
            destroy     : true,
            ajax        : {
              url: url
            },
            columns     : [
              {data: 'timestamp', name: 'timestamp', render: human_readable},
              {data: 'from', name: 'sender.name'},
              {data: 'subject', name: 'subject'},
              {data: 'tocounts', name: 'tocounts', orderable: false, searchable: false},
              {data: 'read', name: 'read', orderable: false, searchable: false},
              {data: 'body', name: 'body.body', visible: false}
            ],
            drawCallback: function () {
              $('img').unveil(100);
              ids_to_names();
              $('[data-toggle="tooltip"]').tooltip();

              $('[data-bm-close][data-bm-open]').on('click', function () {
                var $this = $(this);
                $($this.data('bm-close')).one('hidden.bs.modal', function () {
                  $($this.data('bm-open')).modal('show');
                }).modal('hide');
              });

              // After loading the contracts data, bind a click event
              // on items with the contract-item class.
              $('a.mail-content').on('click', function () {

                // Small hack to get an ajaxable url from Laravel
                var url = $(this).attr('data-url');

                // Perform an ajax request for the contract items
                $.get(url, function (data) {
                  $('#mail-content-result').empty().append(data);
                  $('img').unveil(100);
                  ids_to_names();
                });
              });
            }
          });
        });
      }
    });


  </script>

@endpush
