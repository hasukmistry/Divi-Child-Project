<?php

namespace DiviChild\Classes\UserRoles;

// Creates class if not exists.
if ( ! class_exists('DCPublishers') ) {
    class DCPublishers {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'init', array( $this, 'add_user_role') );

            add_action( 'admin_init', array( $this, 'add_user_caps') );

            add_action( 'admin_menu', array( $this, 'customize_admin_menu') );
        }

        /**
         * This will add custom role to wordpress.
         *
         * @return void
         */
        public function add_user_role() {
            // https://codex.wordpress.org/Function_Reference/add_role
            add_role(
                'publisher',
                __( 'Publisher' ),
                array(
                    'read' => true,  // https://wordpress.org/support/article/roles-and-capabilities/#read
                )
            );
        }

        /**
         * This will add capabilities to publisher role.
         *
         * @return void
         */
        public function add_user_caps() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the publisher role
            $role = get_role( 'publisher' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'upload_files' );
        }

        /**
         * This function will customize menu for this role
         *
         * @return void
         */
        public function customize_admin_menu() {
            $user = wp_get_current_user();

            if ( in_array( 'publisher', (array) $user->roles ) ) {
                //The user has the "publisher" role

                remove_menu_page( 'upload.php' );
            }           
        }
    }
}

new DCPublishers();
