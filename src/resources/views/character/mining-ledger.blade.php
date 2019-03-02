@extends('web::character.layouts.view', ['viewname' => 'mining-ledger', 'breadcrumb' => trans('web::seat.mining')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mining'))

@inject('request', Illuminate\Http\Request')

@section('character_content')
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{ trans('web::seat.mining') }}</a></li>
      <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.mining') }} </a></li>
      @if(auth()->user()->has('character.jobs'))
          <li class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.mining']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_mining') }}"></i>
            </a>
          </li>
        @endif
    </ul>
    <div class="panel-body">
      <table class="table compact table-condensed table-hover table-responsive" id="character-mining-ledger">
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.system') }}</th>
          <th>{{ trans('web::seat.ore') }}</th>
          <th>{{ trans('web::seat.quantity') }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans_choice('web::seat.value',1) }}</th>
        </tr>
        </thead>
      </table>
    </div>
  </div>

  @include('web::character.includes.mining-ledger-modal')
@stop

@push('javascript')
  <script type="text/javascript">
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      character_mining_table.draw();
    });

    function allLinkedCharacters() {
      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }

    var character_mining_table = $('#character-mining-ledger').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('character.view.mining_ledger.data', ['character_id' => $request->character_id]) }}',
          data: function ( d ) {
            d.all_linked_characters = allLinkedCharacters();
          }
        },
        columns: [
          {data: 'date', name: 'date', render: human_readable},
          {data: 'system', name: 'system.itemName'},
          {data: 'type', name: 'type.typeName'},
          {data: 'quantity', name: 'quantity'},
          {data: 'volume', name: 'volume'},
          {data: 'value', name: 'value'},
        ],
        drawCallback: function () {
          $(document).ready(function () {

            // Load images when they are in the viewport
            $("img").unveil(100);

            $("[data-toggle=tooltip]").tooltip();
          });
        }
      });

      $('#detailed-ledger')
          .on('show.bs.modal', function (e) {
            var imgType = $('#detailed-ledger').find('h4.modal-title img').first();
            var imgTypeRegex = /(\/\/image.eveonline.com\/Type\/)([0-9]+)(_32.png)/gi;
            var imgSrc = imgTypeRegex.exec(imgType.attr('src'));

            imgType.attr('src', imgSrc[1] + $(e.relatedTarget).attr('data-type-id') + imgSrc[3]);

            $('#modal-ledger-system-name').text($(e.relatedTarget).attr('data-system-name'));
            $('#modal-ledger-date').text($(e.relatedTarget).attr('data-date'));
            $('#modal-ledger-type-name').text($(e.relatedTarget).attr('data-type-name'));

            var table = $('#hourly-ledger');

            table.DataTable({
              processing: true,
              serverSide: true,
              searching: false,
              ajax: {
                url: $(e.relatedTarget).attr('data-url'),
                data: function ( d ) {
                  d.all_linked_characters = allLinkedCharacters();
                }
              },
              columns : [
                {data: 'time'},
                {data: 'quantity'},
                {data: 'volume'},
                {data: 'value'}
              ]
            });

          })
          .on('hidden.bs.modal', function (e) {
            var table = $('#hourly-ledger').DataTable();
            table.destroy();
          });
  </script>
@endpush
