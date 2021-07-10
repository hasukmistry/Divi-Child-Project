function getKeyByValue(object, value) {
    for (var prop in object) {
        if (object.hasOwnProperty(prop)) {
            if (object[prop] === value)
            return prop;
        }
    }
}
(function($) {
    // we create a copy of the WP inline edit post function
    let $wp_inline_edit = inlineEditPost.edit;

    // and then we overwrite the function with our own code
    inlineEditPost.edit = function( id ) {
        // "call" the original WP edit function
        // we don't want to leave WordPress hanging
        $wp_inline_edit.apply( this, arguments );
        
        // now we take care of our business
 
        // get the post ID
        let $post_id = 0;
        if ( typeof( id ) == 'object' ) {
            $post_id = parseInt( this.getId( id ) );
        }

        if ( $post_id > 0 ) {
            // define the edit row
            let $edit_row = $( '#edit-' + $post_id );
            let $post_row = $( '#post-' + $post_id );

            // get the data
            let $ticket_status = $( '.column-status', $post_row ).text();
            let $rush_delivery = $( '.column-rush_delivery', $post_row ).text();

            let rush_delivery_value = getKeyByValue(editTicket.rush_deliveries, $rush_delivery);

            // populate the data
            $('select[name="ticket_status"] option:contains(' + $ticket_status + ')', $edit_row).attr('selected', 'selected');
            $('input[name="rush_delivery"][value=' + rush_delivery_value + ']', $edit_row).attr('checked', 'checked');
        }
    };
})(jQuery);