<?php

namespace DiviChild\Classes\QuickEdits;

// Creates class if not exists.
if ( ! class_exists('DCAdvertisers') ) {
    class DCAdvertisers {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'quick_edit_custom_box', array( $this, 'custom_quick_edit' ), 10, 2 );
            add_action( 'quick_edit_custom_box', array( $this, 'custom_quick_edit2' ), 10, 2 );

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            add_action( 'save_post', array( $this, 'save_quick_edit_changes' ) );
            add_action( 'save_post', array( $this, 'save_quick_edit_changes2' ) );
        }

        private function populate_dropdown() {
            $options = '';
            $payment_status = get_advertiser_payment_status_list();

            foreach( $payment_status as $key => $value ) {
                $options .= <<<QUICK_EDIT_ADVERTISER
    <option value="{$key}">{$value}</option>
QUICK_EDIT_ADVERTISER;
            }

            return $options;
        }

        private function populate_dropdown2() {
            $options = '';
            $ad_sizes = get_advertiser_ad_size_list();

            foreach( $ad_sizes as $key => $value ) {
                $options .= <<<QUICK_EDIT_ADVERTISER
    <option value="{$key}">{$value}</option>
QUICK_EDIT_ADVERTISER;
            }

            return $options;
        }

        public function custom_quick_edit( $column_name, $post_type ) {
            if ( 'advertiser' !== $post_type ) {
                return;
            }

            if ( 'payment_status' !== $column_name ) {
                return;
            }

            static $printNonce = TRUE;
            if ( $printNonce ) {
                $printNonce = FALSE;
                wp_nonce_field( 'quick_edit_advertiser', 'dc_quick_edit_advertiser' );
            }

            echo <<<QUICK_EDIT_ADVERTISER
<fieldset class="inline-edit-col-right inline-edit-advertiser dc-quick-edit-advertiser">
    <div class="inline-edit-col column-{$column_name}">
        <label class="inline-edit-group">
            <span class="title">Payment Status</span>
            <select name="payment_status">
                {$this->populate_dropdown()}
            </select>
        </label>    
    </div>
</fieldset>
QUICK_EDIT_ADVERTISER;
        }

        public function custom_quick_edit2( $column_name, $post_type ) {
            if ( 'advertiser' !== $post_type ) {
                return;
            }

            if ( 'ad_size' !== $column_name ) {
                return;
            }

            static $printNonce2 = TRUE;
            if ( $printNonce2 ) {
                $printNonce2 = FALSE;
                wp_nonce_field( 'quick_edit_advertiser2', 'dc_quick_edit_advertiser2' );
            }

            echo <<<QUICK_EDIT_ADVERTISER
<fieldset class="inline-edit-col-right inline-edit-advertiser dc-quick-edit-advertiser">
    <div class="inline-edit-col column-{$column_name}">
        <label class="inline-edit-group">
            <span class="title">Ad Size</span>
            <select name="ad_size">
                {$this->populate_dropdown2()}
            </select>
        </label>    
    </div>
</fieldset>
QUICK_EDIT_ADVERTISER;
        }

        public function enqueue_scripts( $hook ) {
            $screen = get_current_screen();

            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'edit-advertiser' === $screen->id ) {
                    $js_file = '/assets/js/dc-admin-quick-edit-advertiser.js';
                    $js_file_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $js_file ));

                    wp_register_script( 'dc-admin-quick-edit-advertiser', get_stylesheet_directory_uri() . $js_file, array( 'jquery' ), $js_file_ver, true );

                    wp_localize_script( 'dc-admin-quick-edit-advertiser', 'editAdvertiser', [] );

                    // Enqueue script with localized data.
                    wp_enqueue_script( 'dc-admin-quick-edit-advertiser' );

                    $theme_css = '/assets/css/dc-admin-quick-edit-advertiser.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'quick-edit-advertiser-theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }

        public function save_quick_edit_changes( $post_id ) {
            $slug = 'advertiser';
             
            if ( $slug !== $_POST['post_type'] ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            $nonce = $_POST['dc_quick_edit_advertiser'];

            if ( ! wp_verify_nonce( $nonce, 'quick_edit_advertiser' ) ) {
                return;
            }

            if ( isset( $_POST['payment_status'] ) && ! empty( $_POST['payment_status'] ) ) {
                update_field( 'payment_status', $_POST['payment_status'], $post_id );
            }
        }

        public function save_quick_edit_changes2( $post_id ) {
            $slug = 'advertiser';
             
            if ( $slug !== $_POST['post_type'] ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            $nonce = $_POST['dc_quick_edit_advertiser2'];

            if ( ! wp_verify_nonce( $nonce, 'quick_edit_advertiser2' ) ) {
                return;
            }

            if ( isset( $_POST['ad_size'] ) && ! empty( $_POST['ad_size'] ) ) {
                update_field( 'ad_size', $_POST['ad_size'], $post_id );
            }
        }
    }
}

new DCAdvertisers();

