<?php

namespace DiviChild\Classes\MetaBoxes;

trait DCPublisherInfoTrait {
    /**
     * Meta box information.
     *
     * @param WP_Post $post Current post object.
     */
    public function publisher_information( $post ) {
        $author_id = $post->post_author ? $post->post_author : null;

        if ( $author_id ) {
            $user_info = get_userdata( $author_id );

            echo sprintf('<div style="margin:10px 0;"></div>');

            echo sprintf('<b>Publication:</b> %s', get_field('publication_name', "user_{$author_id}"));

            echo sprintf('<div style="margin:10px 0;"></div>');

            echo sprintf('<b>Name:</b> %s', ! empty( $user_info->first_name ) ? $user_info->first_name : $user_info->user_login);

            echo sprintf('<div style="margin:8px 0;"></div>');

            echo sprintf('<b>Email:</b>');

            echo sprintf('<input readonly type="text" class="publisher_email" name="publisher_email" id="publisher_email" value="%s" />', $user_info->user_email);

            echo sprintf('<div style="margin:10px 0;"></div>');

            echo sprintf('<a class="button button-primary button-large" href="javascript:copyEmail(\'publisher_email\');">Copy Email</a>');
        } 
    }

    public function enqueue_footer_scripts( $post_type ) {
        $screen = get_current_screen();

        if ( $post_type !== $screen->id ) {
            return;
        }
        ?>
        <script type="text/javascript">
            // script is located inside assets/js folder
            function copyEmail(elementId) {
                /* Get the text field */
                var copyText = document.getElementById(elementId);

                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999); /* For mobile devices */

                /* Copy the text inside the text field */
                document.execCommand("copy");

                /* Alert the copied text */
                console.log("Email Copied: " + copyText.value);
            }
        </script>
        <style type="text/css">
            #publisher_email, #publisher_email:focus, #publisher_email:hover {
                display: inline-block !important;
                width: auto;
                padding: 0;
                border: 0;
                background: transparent;
                position: relative;
                bottom: 2px;
                left: 4px;
                box-shadow:none;
                outline:none;
            }
        </style>
        <?php
    }
}