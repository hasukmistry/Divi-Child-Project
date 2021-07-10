<?php

namespace DiviChild;

// Creates class if not exists.
if ( ! class_exists('DCAutoload') ) {
    class DCAutoload {
        /**
         * This function will autoload classes.
         *
         * @return void
         */
        public static function register() {
            self::classes();
        }

        /**
         * Autoload require classes.
         *
         * @return void
         */
        public static function classes() {
            $class_files = [
                'user-roles/dc-sub-admin.php',
                'user-roles/dc-publishers.php',
                'user-roles/dc-graphic-design.php',
                'user-roles/dc-website-update.php',
                'user-roles/dc-virtual-tours.php',
                'user-roles/dc-support.php',
                'inc/dc-email.php',
                'inc/dc-page-links.php',
                'inc/dc-assignment-info.php',
                'inc/dc-admin-dashboard.php',
                'options-pages/dc-user-settings.php',
                'options-pages/dc-email-templates.php',
                'options-pages/dc-publisher-privacy-policy.php',
                'options-pages/dc-tickets.php',
                'post-types/dc-attachments.php',
                'post-types/dc-issues.php',
                'post-types/dc-submissions.php',
                'post-types/dc-vendors.php',
                'post-types/dc-advertisers.php',
                'post-types/dc-tickets.php',
                'meta-boxes/dc-publisher-info-trait.php',
                'meta-boxes/dc-issue-listings.php',
                // 'meta-boxes/dc-issue-submissions.php',
                'meta-boxes/dc-issue-advertisers.php',
                'meta-boxes/dc-vendor-advertisements.php',
                'meta-boxes/dc-submission-vendors.php',
                'meta-boxes/dc-issue-publisher-info.php',
                'meta-boxes/dc-ticket-publisher-info.php',
                'meta-boxes/dc-ticket-notifications.php',
                'quick-edits/dc-issues.php',
                'quick-edits/dc-advertisers.php',
                'quick-edits/dc-tickets.php',
                'quick-edits/dc-submissions.php',
            ];

            foreach( $class_files as $file ) {
                $class_file = get_stylesheet_directory() . '/classes/' . $file ;

                // load if class file exists
                if ( file_exists( $class_file ) ) {
                    require_once( $class_file );
                }
            }            
        }
    }
}

DCAutoload::register();

// Adds require action hooks and filters.
require_once( get_stylesheet_directory() . '/dc-hooks.php' );

// Adds require custom functions.
require_once( get_stylesheet_directory() . '/dc-functions.php' );

// Adds acf fields
require_once( get_stylesheet_directory() . '/dc-acf-fields.php' );
