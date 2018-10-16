<input type="search" class="form-control input-sm" placeholder="" id="searchbar">

<table class="table compact table-condensed table-responsive assets-table" id="assets-table">
  <thead>
  <tr>
    <th></th>
  </tr>
  </thead>
</table>

@push('javascript')

  <script type="text/javascript">

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

    var table={};

    var assetTable = $('#assets-table').DataTable({
      processing: true,
      serverSide: true,
      //dom: 'ltp',
      ajax      : {
        url: '{{ route('character.view.assets',['character_id' => $request->character_id]) }}',
        data: function ( d ) {
          return $.extend( {}, d, {
            "extra_search": $("#searchbar").val()
          });

        }
      },
      columns   : [
        {data: 'location', name: 'location', orderable: false, searchable: false}
      ],
      info: false,
      "drawCallback" : function () {

        $('#assets-table thead:first').remove();

        $(".location-table").each(function () {

          var location_id = $(this).attr("data-location-id").toString();
          var url= "{{route('character.view.location.assets',['character_id' => $request->character_id, 'location_id' => ':location_id'])}}";
          url = url.replace(':location_id',location_id);


          table[location_id] = $(this).DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            searching: true,
            dom: 'rt',
            info: false,
            ajax      :{
              url: url,
              data: function ( d ) {
                return $.extend( {}, d, {
                  "extra_search": $("#searchbar").val()
                });
              }
            },
            columns   : [
              {orderable: false, data: null, defaultContent: '', searchable: false},
              {data: 'quantity', name: 'quantity', searchable: false},
              {data: 'type', name: 'type', orderable: false, searchable: false}, //TODO: test if sorting/search is possible
              {data: 'volume', name: 'volume', orderable: false, searchable: false},
              {data: 'group', name: 'group', orderable: false, searchable: false},
              {data: 'typeName', name: 'invTypes.typeName', visible: false }
            ],
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
          })
        });

        $('.location-table tbody').on('click', 'td.details-control', function () {

          var location_id = $(this).attr("data-location-id").toString();
          var tr = $(this).closest('tr');
          var row = table[location_id].row(tr);
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
        });



        function initTable(data) {
          $("#assets-contents[data-item-id=" + data.item_id +"]").DataTable({
            processing: true,
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

        //
      }
    });

    $("#searchbar").on('keyup', function () {
      for(var property in table){
        if(table.hasOwnProperty(property)){
          table[property].search(this.value).draw();
        }
      }


    });



    function globalFilter() {


    }

  </script>

@endpush
