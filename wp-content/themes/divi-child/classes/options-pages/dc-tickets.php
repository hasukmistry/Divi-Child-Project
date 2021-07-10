<?php

namespace DiviChild\Classes\OptionsPages;

// Creates class if not exists.
if ( ! class_exists('DCTickets') ) {
    class DCTickets {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'acf/init', array( $this, 'create_ticket_settings_page') );
        }

        public function create_ticket_settings_page() {
            if ( ! is_user_logged_in() ) {
                return;
            }

            // only in admin area, publishers only.
            if ( is_admin() ) {
                $args = array(
                    'page_title' => __('Tickets'),
                    'menu_title' => __('Tickets'),
                    'menu_slug'  => 'dc-tickets',
                    'capability' => 'manage_options',
                    'redirect' 	 => false,
		            'parent'     => 'website-settings'
                );

                if( function_exists('acf_add_options_page') ) { acf_add_options_page( $args ); }
            }
        }
    }
}

new DCTickets();
