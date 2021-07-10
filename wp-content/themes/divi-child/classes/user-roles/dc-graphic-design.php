<?php

namespace DiviChild\Classes\UserRoles;

// Creates class if not exists.
if ( ! class_exists('DCGraphicDesign') ) {
    class DCGraphicDesign {
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
                'graphic_design_lead',
                __( 'Graphic Design: Lead' ),
                array(
                    'read' => true,  // https://wordpress.org/support/article/roles-and-capabilities/#read
                )
            );

            add_role(
                'graphic_design_others',
                __( 'Graphic Design: Others' ),
                array(
                    'read' => true,  // https://wordpress.org/support/article/roles-and-capabilities/#read
                )
            );
        }

        /**
         * This will add capabilities to user roles.
         *
         * @return void
         */
        public function add_user_caps() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the lead role
            $role_lead = get_role( 'graphic_design_lead' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            // $role_lead->add_cap( 'upload_files' );

            // gets the others role
            $role_others = get_role( 'graphic_design_others' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            // $role_others->add_cap( 'upload_files' );
        }
    }
}

new DCGraphicDesign();
