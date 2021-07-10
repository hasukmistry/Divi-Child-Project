<?php

namespace DiviChild\Classes\OptionsPages;

// Creates class if not exists.
if ( ! class_exists('DCUserSettings') ) {
    class DCUserSettings {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'acf/init', array( $this, 'create_user_settings_page') );
        }

        public function create_user_settings_page() {
            if ( ! is_user_logged_in() ) {
                return;
            }

            // only in admin area, publishers only.
            if ( is_admin() ) {
                $user = wp_get_current_user();
                if ( in_array( 'publisher', (array) $user->roles ) ) {
                    $args = array(
                      'page_title' => __('Custom Settings'),
                      'menu_title' => __('Custom Settings'),
                      'menu_slug'  => 'dc-publisher-settings',
                      'capability' => 'read',
                      'post_id'    => 'user_'.get_current_user_id(),
                    );

                    if( function_exists('acf_add_options_page') ) { acf_add_options_page( $args ); }
                }
            }
        }
    }
}

new DCUserSettings();
