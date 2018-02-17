@extends('web::character.layouts.view', ['viewname' => 'mail'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.mail') }}</h3>
    </div>
    <div class="panel-body">

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

    </div>
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

@stop

@push('javascript')

<script>

  $(function () {
    $('table#character-mail').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('character.view.mail.data', ['character_id' => $request->character_id]) }}',
      columns         : [
        {data: 'timestamp', name: 'timestamp', render: human_readable},
        {data: 'from', name: 'from'},
        {data: 'subject', name: 'subject'},
        {data: 'tocounts', name: 'tocounts'},
        {data: 'read', name: 'read'}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);

          ids_to_names();
        });
      }
    });
  });

</script>

@include('web::includes.javascript.id-to-name')

@endpush
