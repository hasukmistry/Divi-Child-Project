<?php

namespace DiviChild\Classes\UserRoles;

// Creates class if not exists.
if ( ! class_exists('DCSubAdmin') ) {
    class DCSubAdmin {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'init', array( $this, 'add_user_role') );

            add_action( 'admin_init', array( $this, 'add_user_caps') );
        }

        /**
         * This will add custom role to wordpress.
         *
         * @return void
         */
        public function add_user_role() {
            // https://codex.wordpress.org/Function_Reference/add_role
            add_role(
                'sub_admin',
                __( 'Sub Admin' ),
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
            $role = get_role( 'sub_admin' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'upload_files' );
        }
    }
}

new DCSubAdmin();
