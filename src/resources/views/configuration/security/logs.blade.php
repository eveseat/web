@extends('web::layouts.grids.12')

@section('title', trans('web::seat.security_logs'))
@section('page_header', trans('web::seat.security_logs'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.security_logs') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="logs" data-page-length=100>
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans_choice('web::seat.user', 1) }}</th>
          <th>{{ trans('web::seat.category') }}</th>
          <th>{{ trans('web::seat.message') }}</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>
@stop

@push('javascript')

<script>

  $(function () {
    $('table#logs').DataTable({
      processing: true,
      serverSide: true,
      ajax      : '{{ route('configuration.security.logs.data') }}',
      columns   : [
        {data: 'created_at', name: 'created_at', render: human_readable},
        {data: 'user', name: 'user', orderable: false, searchable: false},
        {data: 'category', name: 'category'},
        {data: 'message', name: 'message'}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>'
    });
  });

</script>

@endpush
