<?php

namespace DiviChild\Classes\MetaBoxes;

// Creates class if not exists.
if ( ! class_exists('DCIssuePublisherInfo') ) {
    class DCIssuePublisherInfo {
        use DCPublisherInfoTrait;

        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'add_meta_boxes', array( $this, 'information' ) );

            add_action( 'admin_footer', array( $this, 'footer_scripts' ) );
        }

        /**
         * This function will register metabox for issue post type.
         *
         * @return void
         */
        public function information() {
            global $post;
            $user = wp_get_current_user();          
            
            $forcePublisherInfo = false;

            if ( 'issue' === get_post_type() ) {
                $author_id = $post->post_author;

                if ( $user->ID !== $author_id ) {
                    $forcePublisherInfo = true;
                }
            }

            $allowed_roles = array('administrator');
            if ( array_intersect( $allowed_roles, $user->roles ) || $forcePublisherInfo ) {
                // Stuff here for allowed roles
                add_meta_box( 'issue-publisher-info-top', __( 'Publisher', 'divi-child-metabox' ), array( $this, 'display_information' ), 'issue' );

                add_meta_box( 'issue-publisher-info-side', __( 'Publisher', 'divi-child-metabox' ), array( $this, 'display_information' ), 'issue', 'side');
            }
        }

        /**
         * Meta box display callback.
         *
         * @param WP_Post $post Current post object.
         */
        public function display_information( $post ) {
            $this->publisher_information( $post ); 
        }

        public function footer_scripts() {
            $this->enqueue_footer_scripts('issue');           
        }
    }
}

new DCIssuePublisherInfo();