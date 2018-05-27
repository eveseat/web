@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 2))
@section('page_header', trans_choice('web::seat.character', 2))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.character', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-list" data-page-length=100>
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
          <th>{{ trans('web::seat.alliance') }}</th>
          <th>{{ trans('web::seat.security_status') }}</th>
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
      $('table#character-list').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('character.list.data') }}',
        columns         : [
          {data: 'name', name: 'name'},
          {data: 'corporation_id', name: 'corporation_id'},
          {data: 'alliance_id', name: 'alliance_id'},
          {data: 'security_status', name: 'security_status'},
          {data: 'actions', name: 'actions'}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {
            $("img").unveil(100);

            ids_to_names();
          });
        },
        order           : [[0, "asc"]]
      });
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
