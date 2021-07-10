jQuery( document ).ready( function( $ ) {
    // $() will work as an alias for jQuery() inside of this function
    $('.dc-datatable').DataTable({
        "order": [],
        "bLengthChange": false, // length dropdown
        "bFilter": true, // search box
    });
} );    