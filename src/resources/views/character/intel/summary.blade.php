@extends('web::character.intel.layouts.view', ['sub_viewname' => 'summary'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Top Wallet Journal Interactions</h3>
    </div>
    <div class="panel-body">

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

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Top Wallet Transaction Interactions</h3>
    </div>
    <div class="panel-body">

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

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Top Mail Interactions</h3>
    </div>
    <div class="panel-body">

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

  $(function () {
    $('table#character-top-journal-interactions').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('character.view.intel.summary.journal.data', ['character_id' => $request->character_id]) }}',
      columns: [
        {data: 'total', name: 'total', searchable: false},
        {data: 'refTypeName', name: 'refTypeName'},
        {data: 'characterName', name: 'characterName'},
        {data: 'corporationName', name: 'corporationName'},
        {data: 'allianceName', name: 'allianceName'},
      ],
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      }
    });
  });

  $(function () {
    $('table#character-top-transaction-interactions').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('character.view.intel.summary.transactions.data', ['character_id' => $request->character_id]) }}',
      columns: [
        {data: 'total', name: 'total', searchable: false},
        {data: 'characterName', name: 'characterName'},
        {data: 'corporationName', name: 'corporationName'},
        {data: 'allianceName', name: 'allianceName'},
      ],
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      }
    });
  });

  $(function () {
    $('table#character-top-mail-interactions').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('character.view.intel.summary.mail.data', ['character_id' => $request->character_id]) }}',
      columns: [
        {data: 'total', name: 'total', searchable: false},
        {data: 'characterName', name: 'characterName'},
        {data: 'corporationName', name: 'corporationName'},
        {data: 'allianceName', name: 'allianceName'},
      ],
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      }
    });
  });

</script>

@endpush
