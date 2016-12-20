@extends('web::layouts.grids.12')

@section('title', trans('web::seat.joblog'))
@section('page_header', trans('web::seat.joblog'))

@inject('request', 'Illuminate\Http\Request')

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.api_all') }}

      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="joblog-table" data-page-length=25>
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.key_id', 1) }}</th>
          <th>{{ trans('web::seat.enabled') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th></th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')
<script>

  $(function () {
    $('table#joblog-table').DataTable({
      processing: true,
      serverSide: true,
      ajax      : '{{ route('api.key.joblog.data', ['key_id' => $request->key_id]) }}',
      columns   : [
        {data: 'id', name: 'id'},
        {data: 'created_at', name: 'created_at', render: human_readable},
        {data: 'type', name: 'type'},
        {data: 'message', name: 'message'},
      ]
    });
  });

</script>
@endpush
