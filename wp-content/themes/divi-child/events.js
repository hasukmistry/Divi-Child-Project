jQuery( document ).ready(function($) {
    $( "#copy-slug-buttons .copy-slug" ).click(function() {
        var $temp = $("<input>");
        $("body").append($temp);

        $temp.val($(this).data("permalink")).select();

        // execute copy command
        document.execCommand("copy");

        $temp.remove();

        // show status
        $('#copy-slug-buttons > #copy_status').show();

        // fade out status
        setTimeout(function() {
            $('#copy-slug-buttons > #copy_status').fadeOut();
        }, 500 );
    });
});