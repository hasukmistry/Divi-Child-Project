<?php

namespace DiviChild\Classes\QuickEdits;

// Creates class if not exists.
if ( ! class_exists('DCIssues') ) {
    class DCIssues {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'quick_edit_custom_box', array( $this, 'custom_quick_edit' ), 10, 2 );

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            add_action( 'save_post', array( $this, 'save_quick_edit_changes' ) );
        }

        private function populate_dropdown() {
            $options = '';
            $issue_status = get_issue_status_list();

            foreach( $issue_status as $key => $value ) {
                $options .= <<<QUICK_EDIT_ISSUE
    <option value="{$key}">{$value}</option>
QUICK_EDIT_ISSUE;
            }

            return $options;
        }

        public function custom_quick_edit( $column_name, $post_type ) {
            if ( 'issue' !== $post_type ) {
                return;
            }

            if ( 'status' !== $column_name ) {
                return;
            }

            static $printNonce = TRUE;
            if ( $printNonce ) {
                $printNonce = FALSE;
                wp_nonce_field( 'quick_edit_issue', 'dc_quick_edit_issue' );
            }

            echo <<<QUICK_EDIT_ISSUE
<fieldset class="inline-edit-col-right inline-edit-issue dc-quick-edit-issue">
    <div class="inline-edit-col column-{$column_name}">
        <label class="inline-edit-group">
            <span class="title">Issue Status</span>
            <select name="issue_status">
                {$this->populate_dropdown()}
            </select>
        </label>    
    </div>
</fieldset>
QUICK_EDIT_ISSUE;
        }

        public function enqueue_scripts( $hook ) {
            $screen = get_current_screen();

            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'edit-issue' === $screen->id ) {
                    $js_file = '/assets/js/dc-admin-quick-edit-issue.js';
                    $js_file_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $js_file ));

                    wp_register_script( 'dc-admin-quick-edit-issue', get_stylesheet_directory_uri() . $js_file, array( 'jquery' ), $js_file_ver, true );

                    wp_localize_script( 'dc-admin-quick-edit-issue', 'editIssue', [] );

                    // Enqueue script with localized data.
                    wp_enqueue_script( 'dc-admin-quick-edit-issue' );

                    $theme_css = '/assets/css/dc-admin-quick-edit-issue.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'quick-edit-issue-theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }

        public function save_quick_edit_changes( $post_id ) {
            $slug = 'issue';
             
            if ( $slug !== $_POST['post_type'] ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            $nonce = $_POST['dc_quick_edit_issue'];

            if ( ! wp_verify_nonce( $nonce, 'quick_edit_issue' ) ) {
                return;
            }

            if ( isset( $_POST['issue_status'] ) && ! empty( $_POST['issue_status'] ) ) {
                update_field( 'issue_status', $_POST['issue_status'], $post_id );
            }
        }
    }
}

new DCIssues();
