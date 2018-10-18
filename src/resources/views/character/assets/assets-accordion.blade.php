<table class="table compact table-hover table-condensed table-responsive location-table">
  <thead>
  <tr>
    <th></th>
    <th>Quantity</th>
    <th>Type</th>
    <th>Volume</th>
    <th>Group</th>
  </tr>
  </thead>
</table>


@push('javascript')

  <script type="text/javascript">

    var url = "{{route('character.view.assets.accordion', ['character_id' => auth()->user()->id])}}";

    var assetGroupTable = $('.location-table').DataTable({
      processing: true,
      serverSide: true,
      paging: false,
      ajax: {
        url: url,
        data: function ( d ) {
          d.character_ids = '{{auth()->user()->associatedCharacterIds()}}'
        }
      },
      columns   : [
        {orderable: false, data: null, defaultContent: '', searchable: false},
        {data: 'quantity', name: 'quantity', searchable: false},
        {data: 'item', name: 'item', orderable: false, searchable: false}, //TODO: test if sorting/search is possible
        {data: 'volume', name: 'volume', orderable: false, searchable: false},
        {data: 'group', name: 'group', orderable: false, searchable: false},
        {data: 'typeName', name: 'invTypes.typeName', visible: false },
        {data: 'locationName', name: 'locationName', searchable: false, visible: false },
        {data: 'name', name: 'name', visible: false }
      ],
      rowGroup: {
        startRender: function(rows, group) {

          var numberItems = rows.count();
          var volume = rows.data().pluck('type').pluck('volume').reduce(function (a , b) {
            return a + b*1;
          },0);

          return $('<tr/>')
              .append( '<td colspan="5"><b>'+group+'</b><span class="pull-right">'+ numberItems +' items taking '+ abbreviateNumber(volume) +' m&sup3</span></td>' )
        },
        dataSrc: 'locationName'
      },
      createdRow: function(row, data, dataIndex) {
        if(data.quantity == null){
          $(row).find("td:eq(0)")
              .addClass('details-control')
              .attr('data-location-id',data.location_id)
              .append('<button class="btn btn-xs btn-link"><i class="fa fa-plus"></i></button>');
        }
      },
      drawCallback : function () {
        $("img").unveil(100);
      },
    });

    $('.location-table tbody').on('click', 'td.details-control', function (e) {

      var tr = $(this).closest('tr');
      var row = assetGroupTable.row(tr);
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
        tr.next('tr').find('td').css("background-color", "#E5E5E5");
      }
    }).on('draw.dt', function () {
      // remove additonal created group-rows
      $(".dtrg-group").remove();
    });

    function initTable(data) {
      $("#assets-contents[data-item-id=" + data.item_id +"]").DataTable({
        processing: false,
        serverSide: true,
        paging: false,
        info: false,
        searching: false,
        ajax: data.details_url,
        columns: [
          {data: 'quantity', name: 'quantity'},
          {data: 'type', name: 'type', orderable: false, searchable: false},
          {data: 'volume', name: 'volume', orderable: false, searchable: false},
          {data: 'group', name: 'group', orderable: false, searchable: false},
        ],
        drawCallback : function () {
          $("img").unveil(100);
        }
      })
    }

    function template ( d ) {
      // `d` is the original data object for the row
      return  '<table class="table compact table-condensed table-hover table-responsive" id="assets-contents" data-item-id='+ d.item_id +'>'+
          '<thead>'+
          '<tr>'+
          '<th>Quantity</th>'+
          '<th>Item</th>'+
          '<th>Volume</th>'+
          '<th>Group</th>'+
          '<th></th>'+
          '</tr>'+
          '</thead>'+
          '</table>';
    }

    var SI_SYMBOL = ["", "k", "M", "G", "T", "P", "E"];

    function abbreviateNumber(number){

      // what tier? (determines SI symbol)
      var tier = Math.log10(number) / 3 | 0;

      // if zero, we don't need a suffix
      if(tier == 0) return number;

      // get suffix and determine scale
      var suffix = SI_SYMBOL[tier];
      var scale = Math.pow(10, tier * 3);

      // scale the number
      var scaled = number / scale;

      // format number and add suffix
      return scaled.toFixed(1) + suffix;
    }

  </script>

@endpush