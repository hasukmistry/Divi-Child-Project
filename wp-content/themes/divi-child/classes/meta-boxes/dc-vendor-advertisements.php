<?php

namespace DiviChild\Classes\MetaBoxes;

// Creates class if not exists.
if ( ! class_exists('DCVendorAdvertisements') ) {
    class DCVendorAdvertisements {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'add_meta_boxes', array( $this, 'advertisements' ) );
        }

        /**
         * This function will register metabox for issue post type.
         *
         * @return void
         */
        public function advertisements() {
            add_meta_box( 'vendor-advertisements', __( 'Advertisements', 'divi-child-metabox' ), array( $this, 'display_advertisements'), 'vendor' );
        }
              
        /**
         * Meta box display callback.
         *
         * @param WP_Post $vendor Current issue object.
         */
        public function display_advertisements( $vendor ) {
            echo "Work in progress...";
        }
    }
}

new DCVendorAdvertisements();
