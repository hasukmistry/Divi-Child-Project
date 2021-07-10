<?php

namespace DiviChild\Classes\MetaBoxes;

use DiviChild\Classes\Inc\DCEmail;

// Creates class if not exists.
if ( ! class_exists('DCTicketNotifications') ) {
    class DCTicketNotifications {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'save_post', array( $this, 'enable_notification' ), 10, 3 );

            if ( is_admin() ) {                
                add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
                add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );

                // []: Ajax function to notify user.
                add_action( 'wp_ajax_dc_notify_users', array( &$this, 'dc_notify_users' ) );
                add_action( 'wp_ajax_nopriv_dc_notify_users', array( &$this, 'dc_notify_users' ) );

                add_action( 'admin_enqueue_scripts', array( &$this, 'add_scripts' ), 100, 1 );
            }
        }

        /**
         * This will trigger an execution of notifications.
         *
         * @param Integer $ID
         * @param Object $post
         * @return void
         */
        public function enable_notification( $post_id, $post, $update ) {
            // If this is just a revision, don't send the email.
            if ( wp_is_post_revision( $post_id ) ) {
                return;
            }
             
            // Only set for post_type = town!
            if ( 'ticket' !== $post->post_type ) {
                return;
            }
            
            // only when status is publish
            if ( 'publish' !== get_post_status( $post ) ) {
                return;
            }
            
            // lets update meta when town is published
            update_post_meta( $post_id, 'notification_enabled', true );
        }

        /**
         * Meta box initialization.
         */
        public function init_metabox() {
            add_action( 'add_meta_boxes', array( $this, 'add_metabox'  ) );
        }

        /**
         * Adds the meta box.
         */
        public function add_metabox() {
            add_meta_box(
                'notification-box',
                __( 'Notifications' ),
                array( $this, 'render_metabox' ),
                'ticket',
                'side',
                'high'
            );    
        }

        /**
         * Renders the meta box.
         */
        public function render_metabox( $post ) {
            $enabled = get_post_meta( get_the_ID(), 'notification_enabled', true );            
            $enabled = ! empty( $enabled ) ? $enabled : false; 
            
            echo "<div style='margin:10px 0px;'>Notify users assigned to this ticket.</div>";

            $property = 'id="notify-ticket-users"';
            if ( ! $enabled ) {
                $property = 'disabled';
            } else {
                echo sprintf('<div class="ticket-console" id="ticket-console"></div>' );
            }

            echo sprintf('<input %s class="button button-primary button-large" type="button" value="Send Notifications" />', $property);
        }

        /**
         * Ajax function to notify users
         *
         * @return void
         */
        public function dc_notify_users() {
            if ( wp_verify_nonce( $_REQUEST['token'], 'notify-ticket-users' ) ) {
                $ticket_id = isset( $_POST['ticket_id'] ) ? $_POST['ticket_id'] : null;
                $template = isset( $_POST['template'] ) ? $_POST['template'] : null;

                if ( $ticket_id && $template ) {
                    $confirm = DCEmail::send_assignment_notifications($ticket_id, $template);

                    if ( $confirm ) {
                        echo json_encode([
                            'status' => '1',
                        ]);
                    } else {
                        echo json_encode([
                            'status' => '-3',
                        ]);
                    }
                } else {
                    // returns -1 when required fields are missing.
                    echo json_encode([
                        'status' => '-2',
                    ]);
                }
            } else {
                // returns -1 when token does not matches.
                echo json_encode([
                    'status' => '-1',
                ]);
            }

            // stop execution afterward.
			wp_die();
        }

        /**
         * This function will add necessary scripts
         *
         * @return void
         */
        public function add_scripts( $hook ) {
            $screen = get_current_screen();
            
            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'ticket' === $screen->id ) {
                    global $post;
                    $post_id = $post->ID;

                    $js_file = '/assets/js/dc-admin-ticket.js';
                    $js_file_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $js_file ));
                
                    wp_register_script( 'dc-admin-ticket', get_stylesheet_directory_uri() . $js_file, array( 'jquery' ), $js_file_ver, true );

                    $token    = wp_create_nonce( 'notify-ticket-users' );
                    $ajax_url = admin_url( 'admin-ajax.php' );

                    $payload = array(
                        'url'      => $ajax_url,
                        'token'    => $token,
                        'post_id'  => $post_id,
                        'template' => 'ticket_assignment',
                    );

                    wp_localize_script( 'dc-admin-ticket', 'ticket', $payload );

			        // Enqueue script with localized data.
                    wp_enqueue_script( 'dc-admin-ticket' );

                    $theme_css = '/assets/css/dc-admin-ticket.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }

    }
}

new DCTicketNotifications();