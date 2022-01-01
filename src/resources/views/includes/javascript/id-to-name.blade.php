<script type="text/javascript">

  function ids_to_names() {

    var items = [];
    var arrays = [], size = 250;

    $(".id-to-name").each(function () {

      var val = $(this).attr("data-id").toString()
      // special case seeding for link resolution - using href attribute as source instead text
      if ($(this).prop('tagName') === 'A')
          val = /([0-9]+)/.exec($(this).attr('href'))[0];

      //add item to array if it's a valid integer
      if (!isNaN(parseInt(val)))
          items.push(val);
    });

    items = $.unique(items);

    while (items.length > 0)
      arrays.push(items.splice(0, size));

    $.each(arrays, function (index, value) {

      $.ajax({
        type   : 'POST',
        url    : "{{ route('seatcore::support.names.resolve') }}",
        data   : {
          'ids': value.join(',')
        },
        success: function (result) {
          $.each(result, function (id, name) {
            $("span.id-to-name[data-id= '" + id + "']").html(name);
            // special case resolver for link
            $("a.id-to-name[href*='" + id + "']").each(function () {
                this.href = this.href.replace(id, name);
            });
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
    ids_to_names();
  });

</script>
