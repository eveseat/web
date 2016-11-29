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
        {data: 'sentDate', name: 'sentDate', render: human_readable},
        {data: 'senderName', name: 'senderName'},
        {data: 'title', name: 'title'},
        {data: 'tocounts', name: 'senderName'},
        {data: 'read', name: 'senderName'},
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
