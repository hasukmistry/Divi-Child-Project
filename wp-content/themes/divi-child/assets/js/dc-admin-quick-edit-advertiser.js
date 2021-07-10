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
            let $payment_status = $( '.column-payment_status', $post_row ).text();
            let $ad_size = $( '.column-ad_size', $post_row ).text();

            // populate the data
            $('select[name="payment_status"] option:contains(' + $payment_status + ')', $edit_row).attr('selected', 'selected');
            $('select[name="ad_size"] option:contains(' + $ad_size + ')', $edit_row).attr('selected', 'selected');
        }
    };
})(jQuery);