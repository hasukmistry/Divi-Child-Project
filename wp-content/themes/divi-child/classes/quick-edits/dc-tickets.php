<?php

namespace DiviChild\Classes\QuickEdits;

// Creates class if not exists.
if ( ! class_exists('DCTickets') ) {
    class DCTickets {
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
            $ticket_status = get_ticket_status_list();

            foreach( $ticket_status as $key => $value ) {
                $options .= <<<QUICK_EDIT_TICKET
    <option value="{$key}">{$value}</option>
QUICK_EDIT_TICKET;
            }

            return $options;
        }

        private function populate_radio_buttons() {
            $radio_buttons = '';
            $rush_deliveries = get_priority_types();

            foreach( $rush_deliveries as $key => $value ) {
                $radio_buttons .= <<<QUICK_EDIT_TICKET
        <input type="radio" name="rush_delivery" value="{$key}"/> {$value}
QUICK_EDIT_TICKET;
            }

            return $radio_buttons;
        }

        public function custom_quick_edit( $column_name, $post_type ) {
            if ( 'ticket' !== $post_type ) {
                return;
            }

            if ( 'status' !== $column_name ) {
                return;
            }

            static $printNonce = TRUE;
            if ( $printNonce ) {
                $printNonce = FALSE;
                wp_nonce_field( 'quick_edit_ticket', 'dc_quick_edit_ticket' );
            }

            echo <<<QUICK_EDIT_TICKET
<fieldset class="inline-edit-col-right inline-edit-ticket dc-quick-edit-ticket">
    <div class="inline-edit-col column-{$column_name}">
        <label class="inline-edit-group">
            <span class="title">Ticket Status</span>
            <select name="ticket_status">
                {$this->populate_dropdown()}
            </select>
        </label>    
    </div>
</fieldset>
QUICK_EDIT_TICKET;
        }

        public function custom_quick_edit2( $column_name, $post_type ) {
            if ( 'ticket' !== $post_type ) {
                return;
            }

            if ( 'rush_delivery' !== $column_name ) {
                return;
            }

            static $printNonce2 = TRUE;
            if ( $printNonce2 ) {
                $printNonce2 = FALSE;
                wp_nonce_field( 'quick_edit_ticket2', 'dc_quick_edit_ticket2' );
            }

            echo <<<QUICK_EDIT_TICKET
<fieldset class="inline-edit-col-right inline-edit-ticket dc-quick-edit-ticket">
    <div class="inline-edit-col column-{$column_name}">
        <label class="inline-edit-group">
            <span class="title">Rush Delivery</span>
            {$this->populate_radio_buttons()}
        </label>    
    </div>
</fieldset>
QUICK_EDIT_TICKET;
        }

        public function enqueue_scripts( $hook ) {
            $screen = get_current_screen();

            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'edit-ticket' === $screen->id ) {
                    $js_file = '/assets/js/dc-admin-quick-edit-ticket.js';
                    $js_file_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $js_file ));

                    wp_register_script( 'dc-admin-quick-edit-ticket', get_stylesheet_directory_uri() . $js_file, array( 'jquery' ), $js_file_ver, true );

                    wp_localize_script( 'dc-admin-quick-edit-ticket', 'editTicket', [
                        'rush_deliveries' => get_priority_types()
                    ] );

                    // Enqueue script with localized data.
                    wp_enqueue_script( 'dc-admin-quick-edit-ticket' );

                    $theme_css = '/assets/css/dc-admin-quick-edit-ticket.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'quick-edit-ticket-theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }

        public function save_quick_edit_changes( $post_id ) {
            $slug = 'ticket';
             
            if ( $slug !== $_POST['post_type'] ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            $nonce = $_POST['dc_quick_edit_ticket'];

            if ( ! wp_verify_nonce( $nonce, 'quick_edit_ticket' ) ) {
                return;
            }

            if ( isset( $_POST['ticket_status'] ) && ! empty( $_POST['ticket_status'] ) ) {
                update_field( 'ticket_status', $_POST['ticket_status'], $post_id );
            }
        }

        public function save_quick_edit_changes2( $post_id ) {
            $slug = 'ticket';
             
            if ( $slug !== $_POST['post_type'] ) {
                return;
            }

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            $nonce = $_POST['dc_quick_edit_ticket2'];

            if ( ! wp_verify_nonce( $nonce, 'quick_edit_ticket2' ) ) {
                return;
            }

            if ( isset( $_POST['rush_delivery'] ) && ! empty( $_POST['rush_delivery'] ) ) {
                update_field( 'rush_delivery', $_POST['rush_delivery'], $post_id );
            }
        }
    }
}

new DCTickets();
