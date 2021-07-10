<?php

namespace DiviChild\Classes\MetaBoxes;

// Creates class if not exists.
if ( ! class_exists('DCIssueListings') ) {
    class DCIssueListings {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            if ( is_admin() ) { 
                add_action( 'admin_menu', array( $this, 'modify_issue_link_on_admin_menu' ), 100 );

                add_action( 'restrict_manage_posts', array( $this, 'location_filtering'), 10, 1 );

                add_filter( 'parse_query', array( $this, 'filter_request_query' ) , 10);

                add_action( 'admin_enqueue_scripts', array( &$this, 'add_scripts' ), 100, 1 );
            }
        }

        public function location_filtering( $post_type ) {
            if( 'issue' !== $post_type ) {
                return; //filter your post
            }

            $checkedReqSub = '';
            $checkedReqCom = '';

            $request_attr1 = 'reqSub';
            if ( isset($_REQUEST[$request_attr1]) && 'hide' === $_REQUEST[$request_attr1]) {
                $checkedReqSub = 'checked';
            }

            $request_attr2 = 'reqCom';
            if ( isset($_REQUEST[$request_attr2]) && 'hide' === $_REQUEST[$request_attr2] ) {
                $checkedReqCom = 'checked';
            }

            $content = '';
            ob_start();

            echo sprintf('<div class="custom-issue-filters">');
            
            echo sprintf( '<label>Hide Issues by Status: </label>' );
            
            echo sprintf( '<div class="custom-issue-filter"><input type="checkbox" %s name="reqSub" value="hide" /> <label>Selling Advertising</label></div>', $checkedReqSub );
            echo sprintf( '<div class="custom-issue-filter"><input type="checkbox" %s name="reqCom" value="hide" /> <label>Completed</label></div>', $checkedReqCom );

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

            if ( 'issue' !== $query->query['post_type'] ) {
                return $query;
            }

            $allStatuses = get_issue_status_list();

            if( isset($_REQUEST['reqSub']) && 'hide' === $_REQUEST['reqSub'] ) {
                unset($allStatuses['requesting_submissions']);
            }

            if( isset($_REQUEST['reqCom']) && 'hide' === $_REQUEST['reqCom'] ) {
                unset($allStatuses['completed']);
            }

            if ( isset($_REQUEST['reqSub']) || isset($_REQUEST['reqCom']) ) {
                $query->query_vars['meta_query'] = array(
                    array(
                        'key'     => 'issue_status',
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
                if ( 'edit-issue' === $screen->id ) {
                    $theme_css = '/assets/css/dc-admin-edit-issue.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }

        public function modify_issue_link_on_admin_menu() {
            global $menu, $submenu;

            $user = wp_get_current_user();
            $subMenuKey = 'edit.php?post_type=issue';
            $filterLink = 'edit.php?post_type=issue&reqSub=hide&reqCom=hide&filter_action=Filter';
            $filterLinkPublisher = 'edit.php?post_type=issue&reqCom=hide&filter_action=Filter';

            $allowed_roles = array('administrator', 'sub_admin', 'publisher');

            if ( array_intersect( $allowed_roles, $user->roles ) ) {
                if ( in_array('publisher', $user->roles ) ) {
                    $submenu[$filterLinkPublisher] = $submenu[$subMenuKey];
                                  
                    if ( isset($menu[27]) && 'edit_issues' === $menu[27][1] ) {
                        $menu[27][2] = $filterLinkPublisher;
                        $submenu[$filterLinkPublisher][5][2] = $filterLinkPublisher;
                    }   
                } else {
                    $submenu[$filterLink] = $submenu[$subMenuKey];
                                                     
                    // only admin user has the capability to unset menu
                    if ( in_array('administrator', $user->roles ) ) {
                        unset($submenu[$subMenuKey]);
                    }      

                    if ( isset($menu[27]) && 'edit_issues' === $menu[27][1] ) {
                        $menu[27][2] = $filterLink;
                        $submenu[$filterLink][5][2] = $filterLink;
                    }   
                }       
            }
        }
    }
}

new DCIssueListings();
