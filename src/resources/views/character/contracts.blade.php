@extends('web::character.layouts.view', ['viewname' => 'contracts'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.contracts') }}</h3>
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

@stop

@push('javascript')

<script>

  $(function () {
    $('table#character-contracts').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('character.view.contracts.data', ['character_id' => $request->character_id]) }}',
      columns: [
        {data: 'dateIssued', name: 'dateIssued', render: human_readable},
        {data: 'issuerID', name: 'issuerID'},
        {data: 'type', name: 'type'},
        {data: 'status', name: 'status'},
        {data: 'title', name: 'title'},
        {data: 'collateral', name: 'collateral'},
        {data: 'price', name: 'price'},
        {data: 'reward', name: 'reward'},
      ],
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("img").unveil(100);
          ids_to_names();
        });
      }
    });
  });

</script>

@include('web::includes.javascript.id-to-name')

@endpush
