@extends('web::character.layouts.view', ['viewname' => 'mail', 'breadcrumb' => trans('web::seat.mail')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{ trans('web::seat.mail') }}</a></li>
      <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.mail') }}</a></li>
      @if(auth()->user()->has('character.jobs'))
        <li class="pull-right">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.mail']) }}"
             style="color: #000000">
            <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_mail') }}"></i>
          </a>
        </li>
      @endif
    </ul>
    <div class="tab-content">

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
      </div>
    </div>
  </div>

@stop

@push('javascript')

  <script>

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
      character_mail.draw();
    });

    function allLinkedCharacters() {
      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }

    var character_mail = $('table#character-mail').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : {
        url : '{{ route('character.view.mail.data', ['character_id' => $request->character_id]) }}',
        data: function (d) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns     : [
        {data: 'timestamp', name: 'timestamp', render: human_readable},
        {data: 'from', name: 'sender.name'},
        {data: 'subject', name: 'subject'},
        {data: 'body', name: 'body.body', visible: false},
        {data: 'tocounts', name: 'tocounts', orderable: false, searchable: false},
        {data: 'read', name: 'read', orderable: false, searchable: false}
      ],
      drawCallback: function () {

        $('img').unveil(100);
        ids_to_names();

        // After loading the contracts data, bind a click event
        // on items with the contract-item class.
        $('a.mail-content').on('click', function () {

          // Small hack to get an ajaxable url from Laravel
          var url = $(this).attr('data-url');

          // Perform an ajax request for the contract items
          $.get(url, function (data) {
            $('span#mail-content-result').html(data);
            $('img').unveil(100);
            ids_to_names();
          });
        });
      }
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
