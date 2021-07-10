<?php

namespace DiviChild\Classes\Inc;

if ( ! class_exists('DCAdminDashboard') ) {
    class DCAdminDashboard {
        public const DC_RECENT_ISSUES = 'dc_recent_issues';
        public const DC_RECENT_TICKETS = 'dc_recent_tickets';
        private $roles = [
            'website_update_lead',
            'website_update_others',
            'virtual_tours_lead',
            'virtual_tours_others',
            'support_lead',
            'support_others',
            'graphic_design_lead',
            'graphic_design_others',
            'publisher',
        ];

        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'wp_dashboard_setup', array( $this, 'customize_dashboard_setup' ) );

            add_action( 'admin_enqueue_scripts', array( &$this, 'add_scripts' ), 100, 1 );

            add_action( 'admin_init', array( $this, 'add_filters' ) );

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }

        public function add_filters() {
            add_filter( 'screen_layout_columns', array( $this, 'dc_dashboard_screen_layout_columns' ) );

            add_filter( 'get_user_option_screen_layout_dashboard', array( $this, 'number_of_columns' ) );
        }

        public function customize_dashboard_setup() {
            if ( is_admin() ) {
                global $wp_meta_boxes;
                $user = wp_get_current_user();

                if ( array_intersect( $this->roles, (array) $user->roles ) ) {
                    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
                    unset($wp_meta_boxes['dashboard']['normal']['core']['boldgrid_news_widget']);
                    unset($wp_meta_boxes['dashboard']['normal']['core']['boldgrid-notifications']);

                    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
                    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);

                    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
                    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
                    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

                    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
                    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
                    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
                    
                    add_meta_box( 
                        $widget_id = self::DC_RECENT_TICKETS, 
                        $widget_name = esc_html__( 'Recent Tickets', 'dc' ), 
                        $callback = array( $this, 'render_recent_tickets' ), 
                        $screen = get_current_screen(), 
                        $context = 'normal', 
                        $priority = 'core'
                    );
                }

                if ( in_array( 'publisher', (array) $user->roles ) ) {
                    add_meta_box( 
                        $widget_id = self::DC_RECENT_ISSUES, 
                        $widget_name = esc_html__( 'Recent Issues', 'dc' ), 
                        $callback = array( $this, 'render_recent_issues' ), 
                        $screen = get_current_screen(), 
                        $context = 'normal', 
                        $priority = 'core'
                    );
                }
            }
        }

        /**
         * Render issue widget on dashboard
         *
         * @return void
         */
        public function render_recent_issues() {
            $user = wp_get_current_user();

            $query = new \WP_Query( array( 
                'post_type'      => 'issue',
                'posts_per_page' => 30,
                'author'         => $user->ID,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'suppress_filters' => true,
            ) );

            if ( $query->have_posts() ) :
                echo <<<RECENT_ISSUES
<div class="dc-custom-dashboard-widget">
<table class="dc-datatable wp-list-table widefat fixed striped posts" style="width:100%">
<thead>
    <tr>
        <th>Title</th>
        <th>Assigned to</th>
        <th>Publication Name</th>
        <th>Issue Status</th>
    </tr>
</thead>
<tbody id="recent-issues">
RECENT_ISSUES;

                while ( $query->have_posts() ) : $query->the_post();
                    $edit_url  = admin_url('post.php?post=' . get_the_ID() . '&action=edit');
                    $title = get_the_title();

                    $assignment = \DiviChild\Classes\PostTypes\DCIssues::get_assigned_to( get_the_ID() );
                    $assigned_to = '--None--';
                    if ( !empty( $assignment['name'] ) ) {                                                                   
                        $assigned_to = $assignment['name'] . ' - ' .$assignment['email'];
                    }

                    $author_id = get_the_author_meta( 'ID' );
                    $publication_name = get_field('publication_name', "user_{$author_id}");

                    $issue_status = sprintf('%s', get_issue_status( get_field( 'issue_status', get_the_ID() ) ));

                    echo <<<RECENT_ISSUES
    <tr class="">
        <td class="title column-title column-primary page-title" data-colname="Title">
            <a class="row-title" href="{$edit_url}">{$title}</a>
        </td>
        <td>{$assigned_to}</td>
        <td>{$publication_name}</td>
        <td>{$issue_status}</td>
    </tr>
RECENT_ISSUES;
                endwhile;

                echo <<<RECENT_ISSUES
</tbody>
</table>
</div>
RECENT_ISSUES;

                wp_reset_postdata();
            else :
                echo "There are no issues created.";
            endif;
        }

        /**
         * Render ticket widget on dashboard
         *
         * @return void
         */
        public function render_recent_tickets() {
            $user = wp_get_current_user();

            $query_args = [
                'post_type'      => 'ticket',
                'posts_per_page' => 30,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'suppress_filters' => true,
            ];

            $other_roles = [
                'graphic_design_lead'   => 'assigned_to_graphic_designers',
                'website_update_lead'   => 'assigned_to_website_developers',
                'virtual_tours_lead'    => 'assigned_to_virtual_tours',
                'support_lead'          => 'assigned_to_support',
                'graphic_design_others' => 'assigned_to_others1',
                'website_update_others' => 'assigned_to_others2',
                'virtual_tours_others'  => 'assigned_to_others3',
                'support_others'        => 'assigned_to_others4',
            ];

            if ( in_array( 'publisher', (array) $user->roles ) ) {
                $query_args['author'] = $user->ID;
            }

            if ( $result = array_intersect( array_keys($other_roles), (array) $user->roles ) ) {
                $field = $other_roles[ $result[0] ];
                if ( ! empty( $field ) ) {
                    $meta_query = array(
                        array(
                            'key'     => $field,
                            'value'   => $user->ID,
                            'compare' => '=',
                        ),
                    );

                    $query_args['meta_query'] = $meta_query;
                }
            }

            $query = new \WP_Query( $query_args );

            if ( $query->have_posts() ) :
                echo <<<RECENT_TICKETS
<div class="dc-custom-dashboard-widget">
<table class="dc-datatable wp-list-table widefat fixed striped posts" style="width:100%">
<thead>
    <tr>
        <th>Title</th>
        <th>Assigned to</th>
        <th>Publication Name</th>
        <th>Ticket Status</th>
        <th>Rush Delivery</th>        
    </tr>
</thead>
<tbody id="recent-issues">
RECENT_TICKETS;

                while ( $query->have_posts() ) : $query->the_post();
                    $edit_url  = admin_url('post.php?post=' . get_the_ID() . '&action=edit');
                    $title = get_the_title();

                    $assignment = \DiviChild\Classes\PostTypes\DCTickets::get_assigned_to( get_the_ID() );
                    $assigned_to = '--None--';
                    if ( !empty( $assignment['others']['name'] ) ) {                                                                   
                        $assigned_to = $assignment['others']['name'] . ' - ' .$assignment['others']['email'];
                    }

                    $author_id = get_the_author_meta( 'ID' );
                    $publication_name = get_field('publication_name', "user_{$author_id}");

                    $ticket_status = sprintf('%s', get_ticket_status( get_field( 'ticket_status', get_the_ID() ) ));

                    $rush_delivery = get_field( 'rush_delivery', get_the_ID() );
                    $rush_delivery = 'yesrush' === $rush_delivery ? 'Yes' : 'No';

                    echo <<<RECENT_TICKETS
    <tr class="">
        <td class="title column-title column-primary page-title" data-colname="Title">
            <a class="row-title" href="{$edit_url}">{$title}</a>
        </td>
        <td>{$assigned_to}</td>
        <td>{$publication_name}</td>
        <td>{$ticket_status}</td>
        <td>{$rush_delivery}</td>
    </tr>
RECENT_TICKETS;
                endwhile;

                echo <<<RECENT_TICKETS
</tbody>
</table>
</div>
RECENT_TICKETS;

                wp_reset_postdata();
            else :
                echo "There are no issues created.";
            endif;
        }

        public function add_scripts( $hook ) {
            $screen = get_current_screen();
            
            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'dashboard' === $screen->id ) {
                    wp_enqueue_style( 'datatables-style', get_stylesheet_directory_uri() . '/plugins/datatables/datatables.css' );
    
                    wp_enqueue_script( 'datatables-script', get_stylesheet_directory_uri() . '/plugins/datatables/datatables.min.js', array('jquery'), '1.10.20' );
                
                    $wp_datatables     = '/plugins/datatables/dashboard-datatables.js';
                    $wp_datatables_css = '/plugins/datatables/dashboard-datatables.css';

                    $wp_datatables_ver     = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $wp_datatables ));
                    $wp_datatables_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $wp_datatables_css ));

                    wp_enqueue_style( 'wp-datatables-style', get_stylesheet_directory_uri() . $wp_datatables_css, false, $wp_datatables_css_ver );
    
                    wp_register_script( 'wp-datatables', get_stylesheet_directory_uri() . $wp_datatables, array('datatables-script'), $wp_datatables_ver );

                    wp_localize_script( 'wp-datatables', 'input', [
                        'dc_recent_issues_widget_id' => self::DC_RECENT_ISSUES,
                        'dc_recent_tickets_widget_id' => self::DC_RECENT_TICKETS,
                    ] );

                    wp_enqueue_script( 'wp-datatables' );
                }
            }
        }
        
        public function dc_dashboard_screen_layout_columns( $columns  ) {
            if ( is_admin() ) {
                $user = wp_get_current_user();
                if ( array_intersect( $this->roles, (array) $user->roles ) ) {
                    $columns['dashboard'] = 2;
                }

                if ( in_array( 'publisher', (array) $user->roles ) ) {
                    $columns['dashboard'] = 2;
                }
            }  
            return $columns;
        }

        public function number_of_columns( $nr ) {
            if ( is_admin() ) {
                $user = wp_get_current_user();

                if ( array_intersect( $this->roles, (array) $user->roles ) ) {
                    return 2;
                }

                if ( in_array( 'publisher', (array) $user->roles ) ) {
                    return 2;
                }
            }  
            return $nr;
        }

        public function enqueue_scripts( $hook ) {
            $screen = get_current_screen();

            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'dashboard' === $screen->id ) {
                    $theme_css = '/assets/css/dc-admin-dashboard.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'dc-admin-dashboard-theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }
    }
}

new DCAdminDashboard();