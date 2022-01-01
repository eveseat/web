<script type="text/javascript">

  function characters_to_mains() {

    var items = [];
    var arrays = [], size = 250;

    $(".character-id-to-main-character").each(function () {

      var val = $(this).attr("data-character-id").toString();

      //add item to array if it's a valid integer
      if (!isNaN(parseInt(val)))
        items.push(val);
    });

    var items = $.unique(items);

    while (items.length > 0)
      arrays.push(items.splice(0, size));

    $.each(arrays, function (index, value) {

      $.ajax({
        type   : 'POST',
        url    : "{{ route('seatcore::support.main.resolve') }}",
        data   : {
          'ids': value.join(',')
        },
        success: function (result) {
          $.each(result, function (id, name) {
            $("span.character-id-to-main-character[data-character-id= '" + id + "']").html(name);
          });
        },
        error  : function (xhr, textStatus, errorThrown) {
          console.log(xhr);
          console.log(textStatus);
          console.log(errorThrown);
        }
      });
    });
  }

  $(document).ready(function () {
    characters_to_mains();
  });

</script>