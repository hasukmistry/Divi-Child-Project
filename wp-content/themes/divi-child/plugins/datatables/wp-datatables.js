jQuery( document ).ready( function( $ ) {
    // $('.dc-datatable .type-submission').hover(function(){
    //     $(this).find('.edit-submission-status').css('display', 'inline-block');
    // });
    // $('.dc-datatable .type-submission').mouseleave(function(){
    //     $(this).find('.edit-submission-status').css('display', 'none');
    // });
    // $() will work as an alias for jQuery() inside of this function
    $('.dc-datatable').DataTable({
        "order": [],
        "bLengthChange": false, // length dropdown
        "bFilter": true, // search box
        "bInfo": false, // records information
        "columnDefs": [ {
            "targets" : 'no-searching-and-sorting',
            "searchable": false,
            "orderable": false,
        } ],
    } );
} );
