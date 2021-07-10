<?php

namespace DiviChild\Classes\OptionsPages;

// Creates class if not exists.
if ( ! class_exists('DCPublisherPrivacyPolicy') ) {
    class DCPublisherPrivacyPolicy {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'acf/init', array( $this, 'create_publisher_privacy_policy') );
        }

        public function create_publisher_privacy_policy() {
            if ( ! is_user_logged_in() ) {
                return;
            }

            // only in admin area
            if ( is_admin() ) {
                $args = array(
                    'page_title' => __('Publisher Privacy Policy'),
                    'menu_title' => __('Publisher Privacy Policy'),
                    'menu_slug'  => 'dc-publisher-privacy-policy',
                    'capability' => 'manage_options',
                    'redirect' 	 => false,
		            'parent'     => 'website-settings'
                );

                if( function_exists('acf_add_options_page') ) { acf_add_options_page( $args ); }
            }
        }
    }
}

new DCPublisherPrivacyPolicy();
