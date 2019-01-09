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

// put some animation on the caret neat to the user dropdown
$(document).ready(function () {
    $('#user-dropdown').on('show.bs.dropdown', function () {
        $('#user-dropdown').find('i.fa-caret-left').addClass('fa-rotate-270');
    }).on('hide.bs.dropdown', function () {
        $('#user-dropdown').find('i.fa-caret-left').removeClass('fa-rotate-270');
    });
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
  if(tier == 0) return number.toFixed(1);

  // get suffix and determine scale
  var suffix = SI_SYMBOL[tier];
  var scale = Math.pow(10, tier * 3);

  // scale the number
  var scaled = number / scale;

  // format number and add suffix
  return scaled.toFixed(1) + suffix;
}

// Helper function to wrap long strings in multiple lines separated
// str:   The string to be wrapped.
// width: The column width (a number, default: 75)
// brk:   The character(s) to be inserted at every break. (default: ‘n’)
// cut:   The cut: a Boolean value (false by default). See PHP docs for more info. (http://us3.php.net/manual/en/function.wordwrap.php)

function wordwrap( str, width, brk, cut ) {

  brk = brk || 'n';
  width = width || 75;
  cut = cut || false;

  if (!str) { return str; }

  var regex = '.{1,' +width+ '}(\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\S+?(\s|$)');

  return str.match( RegExp(regex, 'g') ).join( brk );

}
