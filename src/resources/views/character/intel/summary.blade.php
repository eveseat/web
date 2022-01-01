@extends('web::character.intel.layouts.view', ['sub_viewname' => 'summary', 'breadcrumb' => trans('web::seat.intel')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans_choice('web::seat.character', 2) }}
      </h3>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control" style="width: 100%;">
          @if($character->refresh_token)
            @foreach($character->refresh_token->user->characters as $character_info)
              @if($character_info->character_id == $character->character_id)
                <option selected="selected" value="{{ $character_info->character_id }}">{{ $character_info->name }}</option>
              @else
                <option value="{{ $character_info->character_id }}">{{ $character_info->name }}</option>
              @endif
            @endforeach
          @else
            <option selected="selected" value="{{ $character->character_id }}">{{ $character->name }}</option>
          @endif
        </select>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Top Wallet Journal Interactions</h3>
    </div>
    <div class="card-body">

      <table class="table table-condensed table-hover table-striped"
             id="character-top-journal-interactions" data-page-length=10>
        <thead>
          <tr>
            <th>Total</th>
            <th>Type</th>
            <th>Party Name</th>
            <th>Party Corp</th>
            <th>Party Alliance</th>
            <th>Party Faction</th>
            <th></th>
          </tr>
        </thead>
      </table>

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Top Wallet Transaction Interactions</h3>
    </div>
    <div class="card-body">

      <table class="table table-condensed table-hover table-striped"
             id="character-top-transaction-interactions" data-page-length=10>
        <thead>
          <tr>
            <th>Total</th>
            <th>Party Name</th>
            <th>Party Corp</th>
            <th>Party Alliance</th>
            <th>Party Faction</th>
            <th></th>
          </tr>
        </thead>
      </table>

    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Top Mail Interactions</h3>
    </div>
    <div class="card-body">

      <table class="table table-condensed table-hover table-striped"
             id="character-top-mail-interactions" data-page-length=10>
        <thead>
          <tr>
            <th>Total</th>
            <th>Character Name</th>
            <th>Character Corp</th>
            <th>Character Alliance</th>
            <th>Character Faction</th>
            <th></th>
          </tr>
        </thead>
      </table>

    </div>
  </div>

  <!-- Transaction Content Modal -->
  @include('web::character.intel.modals.transaction')

  <!-- Journal Content Modal -->
  @include('web::character.intel.modals.journal')

  <!-- Top Mail Content Modal -->
  @include('web::character.intel.modals.mail')

@stop

@push('javascript')

  <script>
      $(document).ready(function() {
          $('#dt-character-selector')
              .select2()
              .on('change', function () {
                  character_top_journal_table.ajax.reload();
                  character_top_transactions_table.ajax.reload();
                  character_top_mails_table.ajax.reload();
              });
      });
  </script>

  <script>
    var character_top_journal_table, character_top_transactions_table, character_top_mails_table,
        character_journal, character_transactions, character_mails;

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
      character_top_journal_table.draw();
      character_top_transactions_table.draw();
      character_top_mails_table.draw();
    });

    character_top_journal_table = $('table#character-top-journal-interactions').DataTable({
      processing  : true,
      serverSide  : true,
      searching   : false,
      ordering    : false,
      ajax        : {
        url : '{{ route('seatcore::character.view.intel.summary.journal.data', ['character' => $character]) }}',
        data: function (d) {
          d.characters = $('#dt-character-selector').val();
        }
      },
      columns: [
        {data: 'total', name: 'total', searchable: false},
        {data: 'ref_type', name: 'ref_type'},
        {data: 'entity_name', name: 'entity_name'},
        {data: 'corporation_id', name: 'corporation_id'},
        {data: 'alliance_id', name: 'alliance_id'},
        {data: 'faction_id', name: 'faction_id'},
        {data: 'action', name: 'action'}
      ],
      createdRow: function (row, data) {
        if (data.is_in_group === '1'){
          $(row).addClass('info')
        }
      },
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();
      }
    });

    character_top_transactions_table = $('table#character-top-transaction-interactions').DataTable({
      processing  : true,
      serverSide  : true,
      searching   : false,
      ordering    : false,
      ajax        : {
        url : '{{ route('seatcore::character.view.intel.summary.transactions.data', ['character' => $character]) }}',
        data: function (d) {
          d.characters = $('#dt-character-selector').val();
        }
      },
      columns     : [
        {data: 'total', name: 'total', searchable: false},
        {data: 'entity_name', name: 'entity_name'},
        {data: 'corporation_id', name: 'corporation_id'},
        {data: 'alliance_id', name: 'alliance_id'},
        {data: 'faction_id', name: 'faction_id'},
        {data: 'action', name: 'action'}
      ],
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();
      }
    });

    character_top_mails_table = $('table#character-top-mail-interactions').DataTable({
      processing  : true,
      serverSide  : true,
      searching   : false,
      ordering    : false,
      ajax        : {
        url : '{{ route('seatcore::character.view.intel.summary.mail.data', ['character' => $character]) }}',
        data: function (d) {
          d.characters = $('#dt-character-selector').val();
        }
      },
      columns     : [
        {data: 'total', name: 'total', searchable: false},
        {data: 'character_id', name: 'character_id'},
        {data: 'corporation_id', name: 'corporation_id'},
        {data: 'alliance_id', name: 'alliance_id'},
        {data: 'faction_id', name: 'faction_id'},
        {data: 'action', name: 'action'}
      ],
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();
      }
    });

    $('#journalContentModal')
      .on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body table');

          character_journal = body.DataTable({
              processing  : true,
              serverSide  : true,
              destroy     : true,
              ajax        : {
                  url: $(e.relatedTarget).data('url')
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
      })
      .on('hide.bs.modal', function () {
          character_journal.destroy();
      });

    $('#transactionContentModal')
      .on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body table');

        character_transactions = body.DataTable({
          processing  : true,
          serverSide  : true,
          destroy     : true,
          ajax        : {
              url: $(e.relatedTarget).data('url')
          },
          columns     : [
              {data: 'date', name: 'date'},
              {data: 'is_buy', searchable: false},
              {data: 'type.typeName', name: 'type.typeName'},
              {data: 'location.name', name: 'location.name'},
              {data: 'unit_price', name: 'unit_price'},
              {data: 'quantity', name: 'quantity'},
              {data: 'total', name: 'total'},
              {data: 'party.name', name: 'party.name'}
          ],
          drawCallback: function () {
              $('img').unveil(100);
              ids_to_names();
              $('[data-toggle="tooltip"]').tooltip();
          }
        });
      })
      .on('hide.bs.modal', function () {
          character_transactions.destroy();
      });

    $('#topMailContentModal')
      .on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body table');

          character_mails = body.DataTable({
              processing  : true,
              serverSide  : true,
              destroy     : true,
              ajax        : {
                  url: $(e.relatedTarget).data('url')
              },
              columns     : [
                  {data: 'timestamp', name: 'timestamp', render: human_readable},
                  {data: 'sender.name', name: 'sender.name'},
                  {data: 'subject', name: 'subject'},
                  {data: 'recipients', name: 'recipients', orderable: false, searchable: false},
                  {data: 'read', name: 'read', orderable: false, searchable: false},
                  {data: 'body.body', name: 'body.body', visible: false}
              ],
              drawCallback: function () {
                  $('img').unveil(100);
                  ids_to_names();
                  $('[data-toggle="tooltip"]').tooltip();
              }
          });
      })
      .on('hide.bs.modal', function () {
         character_mails.destroy();
      });

    $('#mail-content')
      .on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
              body.html(data);
            });
        });
  </script>

@endpush
