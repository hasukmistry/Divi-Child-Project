<?php

namespace DiviChild\Classes\MetaBoxes;

// Creates class if not exists.
if ( ! class_exists('DCTicketListings') ) {
    class DCTicketListings {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            if ( is_admin() ) { 
                add_action( 'admin_menu', array( $this, 'modify_ticket_link_on_admin_menu' ), 100 );

                add_action( 'restrict_manage_posts', array( $this, 'location_filtering'), 10, 1 );

                add_filter( 'parse_query', array( $this, 'filter_request_query' ) , 10);

                add_action( 'admin_enqueue_scripts', array( &$this, 'add_scripts' ), 100, 1 );
            }
        }

        public function location_filtering( $post_type ) {
            if( 'ticket' !== $post_type ) {
                return; //filter your post
            }

            $checkedReqCom = '';

            $request_attr2 = 'reqCom';
            if ( isset($_REQUEST[$request_attr2]) && 'hide' === $_REQUEST[$request_attr2] ) {
                $checkedReqCom = 'checked';
            }

            $content = '';
            ob_start();

            echo sprintf('<div class="custom-ticket-filters">');
            
            echo sprintf( '<label>Hide Tickets by Status: </label>' );
            
            echo sprintf( '<div class="custom-ticket-filter"><input type="checkbox" %s name="reqCom" value="hide" /> <label>Completed</label></div>', $checkedReqCom );

            echo sprintf('</div>');

            $content .= ob_get_clean();
            ob_flush();

            echo $content;
        }

        public function filter_request_query($query) {
            //modify the query only if it admin and main query.
            if ( !(is_admin() AND $query->is_main_query()) ) { 
                return $query;
            }

            if ( 'ticket' !== $query->query['post_type'] ) {
                return $query;
            }

            $allStatuses = get_ticket_status_list();

            if( isset($_REQUEST['reqCom']) && 'hide' === $_REQUEST['reqCom'] ) {
                unset($allStatuses['completed_ticket']);
            }

            if ( isset($_REQUEST['reqCom']) ) {
                $query->query_vars['meta_query'] = array(
                    array(
                        'key'     => 'ticket_status',
                        'value'   => array_keys( $allStatuses ),
                        'compare' => 'IN',
                    ),
                );
            }

            return $query;
        }

        public function add_scripts( $hook ) {
            $screen = get_current_screen();
            
            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'edit-ticket' === $screen->id ) {
                    $theme_css = '/assets/css/dc-admin-edit-ticket.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }

        public function modify_ticket_link_on_admin_menu() {
            global $menu, $submenu;

            $user = wp_get_current_user();
            $subMenuKey = 'edit.php?post_type=ticket';
            $filterLink = 'edit.php?post_type=ticket&reqCom=hide&filter_action=Filter';
            $filterLinkPublisher = $filterLink;

            $allowed_roles = array('administrator', 'sub_admin', 'publisher');

            if ( array_intersect( $allowed_roles, $user->roles ) ) {
                if ( in_array('publisher', $user->roles ) ) {
                    $submenu[$filterLinkPublisher] = $submenu[$subMenuKey];
                                  
                    if ( isset($menu[30]) && 'edit_tickets' === $menu[30][1] ) {
                        $menu[30][2] = $filterLinkPublisher;
                        $submenu[$filterLinkPublisher][5][2] = $filterLinkPublisher;
                    }   
                } else {
                    $submenu[$filterLink] = $submenu[$subMenuKey];
                                                     
                    // only admin user has the capability to unset menu
                    if ( in_array('administrator', $user->roles ) ) {
                        unset($submenu[$subMenuKey]);
                    }      

                    if ( isset($menu[30]) && 'edit_tickets' === $menu[30][1] ) {
                        $menu[30][2] = $filterLink;
                        $submenu[$filterLink][5][2] = $filterLink;
                    }   
                }
            }
        }
    }
}

new DCTicketListings();
