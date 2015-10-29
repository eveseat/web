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
    event.preventDefault()
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
$(document).ready(function() {
    $("img").unveil(100);
});

// Enable bootstrap popovers
$("[data-toggle=popover]").popover();
