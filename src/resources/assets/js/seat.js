// Add the X-CSRF Token header to ajax requests
// Ref: http://laravel.com/docs/5.1/routing#csrf-x-csrf-token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Generic 'confirm' dialog code for forms.
// Make your submit button part of class confirmform, and viola
var currentForm;
$(document).on("click", ".confirmform", function (e) {
    currentForm = $(this).closest("form");
    e.preventDefault();
    bootbox.confirm("Are you sure you want to continue?", function (confirmed) {
        if (confirmed) {
            currentForm.submit();
        }
    });
});

// Generic 'confirm' dialog code for links.
// Make your link button part of class confirmlink, and viola
$(document).on("click", "a.confirmlink", function (event) {
    event.preventDefault();
    var url = $(this).attr("href");
    bootbox.confirm("Are you sure you want to continue?", function (confirmed) {
        if (confirmed) {
            window.location = url;
        }
    });
});

// Init the jQuery unveil plugin. If the
// viewport come into 100px, start loading
// the image
//
// http://luis-almeida.github.io/unveil/
$(document).ready(function () {
    $("img").unveil(100);
});

// Enable bootstrap popovers
$("[data-toggle=popover]").popover();

// Enable bootstrap tooltips
$("[data-toggle=tooltip]").tooltip();

// Initialize DataTables on <table> tags
// with the 'datatable' class.
//
// https://www.datatables.net/
$(document).ready(function () {
    $("table.datatable").DataTable({
        paging: false,
        order: []
    });
});

// Configure some defaults for Datatables
$.extend(true, $.fn.dataTable.defaults, {
    responsive: true,
    autoWidth: false,
    order: [[0, 'desc']]
});

// Helper function to mimic the PHP human_readable method
function human_readable(data, type, row) {
    if (type == 'display') {
        var date = moment.utc(data, "YYYY-MM-DD hh:mm:ss").fromNow();
        return '<span data-toggle="tooltip" data-placement="top" title="' + data + '">' + date + "</span>";
    }

    return data;
}

// Helper function to abbreviate numbers to their SI suffix
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