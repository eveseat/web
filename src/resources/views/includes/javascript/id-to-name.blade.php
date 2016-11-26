<script>

  function ids_to_names() {

    var items = [];
    var arrays = [], size = 250;

    $('[rel="id-to-name"]').each(function () {
      //add item to array
      items.push($(this).text());
    });

    var items = $.unique(items);

    while (items.length > 0)
      arrays.push(items.splice(0, size));

    $.each(arrays, function (index, value) {

      $.ajax({
        type: 'POST',
        url: "{{ route('support.names.resolve') }}",
        data: {
          'ids': value.join(',')
        },
        success: function (result) {
          $.each(result, function (id, name) {

            $("span:contains('" + id + "')").html(name);
          })
        },
        error: function (xhr, textStatus, errorThrown) {
          console.log(xhr);
          console.log(textStatus);
          console.log(errorThrown);
        }
      });
    });
  }

  $(document).ready(function () {
    ids_to_names();
  })

</script>
