@extends('web::character.layouts.view', ['viewname' => 'assets', 'breadcrumb' => trans('web::seat.assets')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{trans_choice('web::seat.character',1)}} {{ trans('web::seat.assets') }}</a></li>
      <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.assets') }}</a></li>
      @if(auth()->user()->has('character.jobs'))
        <li class="pull-right">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.assets']) }}"
             style="color: #000000">
            <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_assets') }}"></i>
          </a>
        </li>
      @endif
    </ul>
    <div class="tab-content">
      <table id="characterTable" class="table compact table-hover table-condensed table-responsive location-table">
        <thead>
        <tr>
          <th></th>
          <th>{{ trans('web::seat.quantity') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans_choice('web::seat.group',1) }}</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- /.tab-content -->
  </div>

@stop

@push('javascript')

  <script type="text/javascript">

    var url = "{{route('character.view.assets.details', ['character_id' => request()->character_id])}}";

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

      var target = $(e.target).data("characters"); // activated tab
      assetGroupTable.draw();
    });

    function allLinkedCharacters() {

      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';

    }

    var assetGroupTable = $('.location-table').DataTable({
      scrollY: '50vh',
      processing: true,
      serverSide: true,
      pageLength: 50,
      ajax: {
        url: url,
        data: function ( d ) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns   : [
        {orderable: false, data: null, defaultContent: '', searchable: false},
        {data: 'quantity', name: 'quantity', searchable: false},
        {data: 'item', name: 'item', orderable: false, searchable: false},
        {data: 'volume', name: 'volume', orderable: false, searchable: false},
        {data: 'group', name: 'group', orderable: false, searchable: false},
        {data: 'typeName', name: 'invTypes.typeName', visible: false },
        {data: 'locationName', name: 'locationName', searchable: false, visible: false },
        {data: 'name', name: 'name', visible: false },
        {data: 'character_id', name: 'character_id', visible: false },
      ],
      rowGroup: {

        startRender: function(rows, group) {

          var numberItems = rows.count();
          var volume = rows.data().pluck('type').pluck('volume').reduce(function (a , b) {
            return a + b*1;
          },0);

          return $('<tr/>')
              .append( '<td colspan="5"><b>'+group+'</b><span class="pull-right">'+ numberItems +' {{ trans('web::seat.items_taking') }} '+ abbreviateNumber(volume) +' m&sup3</span></td>' )
        },
        dataSrc: 'locationName'
      },
      createdRow: function(row, data, dataIndex) {
        if(data.quantity == null){
          $(row).find("td:eq(0)")
              .addClass('details-control')
              .append('<button class="btn btn-xs btn-link"><i class="fa fa-plus"></i></button>');
        }
      },
      drawCallback : function () {
        $("img").unveil(100);
      },
    });

    assetGroupTable.on('click', 'td.details-control', function () {

      var td = $(this);
      var table = $(td).closest('table');
      var row = $(table).DataTable().row(td.closest('tr'));
      var tr = $(this).closest('tr');
      var symbol = tr.find('i');

      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        symbol.removeClass("fa-minus").addClass("fa-plus");

        tr.removeClass('shown').css("background-color", "");
      } else {
        // Open this row
        symbol.removeClass("fa-plus").addClass("fa-minus");

        row.child(template(row.data())).show();
        initTable(row.data());


        tr.addClass('shown').css("background-color", "#D4D4D4"); // Heading Color;
        tr.next('tr').css("background-color", "#E5E5E5");
      }
    });

    function template ( d ) {
      return d.content;
    }
    function initTable(data) {

      $("table#assets-contents[data-item-id=" + data.item_id +"]").DataTable({
        processing: true,
        paging: false,
        info: false,
        searching: false,
        columns: [
          {orderable: false, data: null, defaultContent: '', searchable: false},
          {data: 'quantity', name: 'quantity'},
          {data: 'type', name: 'type', orderable: false, searchable: false},
          {data: 'volume', name: 'volume', orderable: false, searchable: false},
          {data: 'group', name: 'group', orderable: false, searchable: false},
          {data: 'content', name: 'group', orderable: false, searchable: false, visible: false},
        ],
        createdRow: function(row, data, dataIndex) {

          if(data.quantity === ""){

            $(row).find("td:eq(0)")
                .addClass('details-control')
                .attr('data-location-id', data.item_id )
                .attr('data-origin', data.location_id )
                .append('<button class="btn btn-xs btn-link"><i class="fa fa-plus"></i></button>');
          }
        },
        drawCallback : function () {

          $("img").unveil(100);
          // remove additonal created group-rows
          $(".dtrg-group").remove();
        },
      });
    }

  </script>

@endpush