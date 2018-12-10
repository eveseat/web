@extends('web::character.intel.layouts.view', ['sub_viewname' => 'summary'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{trans_choice('web::seat.character',1)}}</a></li>
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

      <hr>

      <h3 class="panel-title">Top Mail Interactions</h3>

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
      url: '{{ route('character.view.intel.summary.journal.data', ['character_id' => $request->character_id]) }}',
      data: function ( d ) {
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
    drawCallback: function () {

      $('img').unveil(100);
      ids_to_names();

    }
  });

  var characterTopTransactionTable = $('table#character-top-transaction-interactions').DataTable({
    processing  : true,
    serverSide  : true,
    searching   : false,
    ordering    : false,
    ajax        : {
      url: '{{ route('character.view.intel.summary.transactions.data', ['character_id' => $request->character_id]) }}',
      data: function ( d ) {
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
    }
  });


  var characterTopMailTable = $('table#character-top-mail-interactions').DataTable({
    processing  : true,
    serverSide  : true,
    searching   : false,
    ordering    : false,
    ajax        : {
      url: '{{ route('character.view.intel.summary.mail.data', ['character_id' => $request->character_id]) }}',
      data: function ( d ) {
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
    }
  });


</script>

@endpush
