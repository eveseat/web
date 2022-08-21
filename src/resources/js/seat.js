// Add the X-CSRF Token header to ajax requests
// Ref: http://laravel.com/docs/5.1/routing#csrf-x-csrf-token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Generic 'confirm' dialog code for forms.
// Make your submit button part of class confirmform, and viola
// Optional: You can describe the action by adding a "data-seat-action" attribute.
var currentForm;
$(document).on("click", ".confirmform", function (e) {
    if ($(this).attr('form') === undefined) {
        currentForm = $(this).closest("form");
    } else {
        currentForm = $('#'.concat($(this).attr('form')))
    }
    e.preventDefault();
    var action = $(this).data('seat-action');
    var message = 'Are you sure you want to continue?';
    if (typeof action !== 'undefined') {
        message = `Are you sure you want to ${action}?`;
    }
    bootbox.confirm(message, function (confirmed) {
        if (confirmed) {
            currentForm.submit();
        }
    });
});

// Deletion 'confirm' dialog.
// Make your submit button part of class confirmdelete, and viola.
// You can add an entity name by adding a "data-seat-entity" attribute.
document.addEventListener('click', function (e) {
    if (e.target.matches('.confirmdelete')) {
        let entity = e.target.dataset.seatEntity;
        let currentForm = (e.target.getAttribute('form') === null) ?
            e.target.closest('form') :
            document.querySelector('#'.concat(e.target.getAttribute('form')));

        e.preventDefault();

        bootbox.confirm({
            size: 'small',
            message: `<div class="modal-status bg-danger"></div><div class="modal-body text-center py-4"><i class="fas fa-2x fa-exclamation-triangle text-danger"></i><h3>Are you sure?</h3><div class="text-muted">Do you really want to remove this ${ typeof entity === undefined ? 'item' : entity }? What you've done cannot be undone.</div></div>`,
            closeButton: false,
            centerVertical: true,
            buttons: {
                confirm: {
                    label: 'Delete item',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-default'
                },
            },
            callback: function (result) {
                if (result) currentForm.submit();
            }
        });
    }
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
$.extend(true, DataTable.defaults, {
    responsive: true,
    autoWidth: false,
    order: [[0, 'desc']],
    dom: '<"card-body border-bottom py-3"<"d-flex"<"text-muted"l><"ms-auto"B><"ms-auto text-muted"f>>><"table-responsive"t><"card-footer d-flex align-items-center"i<"m-0 ms-auto"p>>',
    language: {
        paginate: {
            previous: '<i class="fas fa-chevron-left me-1"></i> ' + DataTable.defaults.oLanguage.oPaginate.sPrevious.toLowerCase(),
            next: DataTable.defaults.oLanguage.oPaginate.sNext.toLowerCase() + ' <i class="fas fa-chevron-right ms-1"></i>'
        }
    }
});

$.extend(true, DataTable.Buttons.defaults, {
    dom: {
        container: {
            className: 'dt-buttons btn-group btn-group-sm flex-wrap',
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function (e) {
        if (e.target.matches('.dt-filters')) {
            e.target.classList.contains('active') ? e.target.classList.remove('active') : e.target.classList.add('active');
            window.LaravelDataTables['dataTableBuilder'].ajax.reload();
        }
    });
});

// put some animation on the caret neat to the user dropdown
$(document).ready(function () {
    $('#user-dropdown').on('show.bs.dropdown', function () {
        $('#user-dropdown').find('i.fa-caret-left').addClass('fa-rotate-270');
    }).on('hide.bs.dropdown', function () {
        $('#user-dropdown').find('i.fa-caret-left').removeClass('fa-rotate-270');
    });

    $('button[data-widget="esi-update"]').on('click', function() {
        var button = $(this);
        button.find('i').addClass('fa-spin');

        $.ajax({
            url: button.data('url'),
            method: 'POST',
            data: {
                type: button.data('type'),
                entity_id: button.data('entity'),
                job_name: button.data('job')
            },
            success: function() {
                button.find('i').removeClass('fa-spin');
            },
            error: function(e) {
                button.addClass('btn-danger');
                button.removeClass('btn-light');

                console.error(e);
            }
        });
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
