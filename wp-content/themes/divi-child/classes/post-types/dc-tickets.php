<?php

namespace DiviChild\Classes\PostTypes;

// Creates class if not exists.
if ( ! class_exists('DCTickets') ) {
    class DCTickets {
        /**
         * This will save leader meta query to filter result
         *
         * @var array
         */
        private $leader_meta_query = [];

        /**
		 * Pages which is not allowed by leaders to view.
		 *
		 * @var array
		 */
		private $leader_restricted_pages = [
			'post-new.php' // disallow creating new tickets
        ];

        /**
         * This will save others meta query to filter result
         *
         * @var array
         */
        private $others_meta_query = [];

        /**
		 * Pages which is not allowed by others to view.
		 *
		 * @var array
		 */
		private $others_restricted_pages = [
			'post-new.php' // disallow creating new tickets
        ];
        
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'init', array( $this, 'create_post_type') );

            add_action( 'admin_init', array( $this, 'set_admin_rights') );

            add_action( 'admin_init', array( $this, 'set_sub_admin_rights') );

            add_action( 'admin_init', array( $this, 'set_publisher_rights') );

            add_action( 'admin_init', array( $this, 'set_lead_roles') );

            add_action( 'admin_init', array( $this, 'set_other_roles') );

            add_filter( 'admin_init', array( $this, 'restrict_page_for_leaders' ) );

            add_filter( 'admin_init', array( $this, 'restrict_page_for_others' ) );

            // hide add new buttons
            add_action('admin_menu', array( $this,'hide_add_new_buttons' ) );

            // only for publishers.
            add_action( 'pre_get_posts', array( $this, 'display_publisher_posts_only') );

            // only for lead roles
            add_action( 'pre_get_posts', array( $this, 'display_leaders_posts_only') );

            // only for other roles
            add_action( 'pre_get_posts', array( $this, 'display_others_posts_only') );

            $post_type = 'ticket';
            add_filter( "manage_{$post_type}_posts_columns", array( $this, 'ticket_custom_columns_list' ) );
            add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'ticket_custom_columns_values' ), 10, 2 );

            // auto assignments
            add_action('acf/save_post', array( $this, 'auto_assign') );
        }

        /**
         * This will add custom post type to wordpress.
         *
         * @return void
         */
        public function create_post_type() {
            // https://developer.wordpress.org/reference/functions/register_post_type/

            $labels = array(
                'name'                  => _x( 'Tickets', 'Post type general name', 'dc-tickets' ),
                'singular_name'         => _x( 'Ticket', 'Post type singular name', 'dc-tickets' ),
                'menu_name'             => _x( 'Tickets', 'Admin Menu text', 'dc-tickets' ),
                'name_admin_bar'        => _x( 'Ticket', 'Add New on Toolbar', 'dc-tickets' ),
                'add_new'               => __( 'Add New', 'dc-tickets' ),
                'add_new_item'          => __( 'Add New Ticket', 'dc-tickets' ),
                'new_item'              => __( 'New Ticket', 'dc-tickets' ),
                'edit_item'             => __( 'Edit Ticket', 'dc-tickets' ),
                'view_item'             => __( 'View Ticket', 'dc-tickets' ),
                'view_items'            => __( 'View Tickets', 'dc-tickets' ),
                'all_items'             => __( 'All Tickets', 'dc-tickets' ),
                'search_items'          => __( 'Search Tickets', 'dc-tickets' ),
                'parent_item_colon'     => __( 'Parent Tickets:', 'dc-tickets' ),
                'not_found'             => __( 'No tickets found.', 'dc-tickets' ),
                'not_found_in_trash'    => __( 'No tickets found in Trash.', 'dc-tickets' ),
                'featured_image'        => _x( 'Ticket\'s Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dc-tickets' ),
                'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dc-tickets' ),
                'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dc-tickets' ),
                'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'dc-tickets' ),
                'archives'              => _x( 'Ticket archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'dc-tickets' ),
                'insert_into_item'      => _x( 'Insert into ticket', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'dc-tickets' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this ticket', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'dc-tickets' ),
                'filter_items_list'     => _x( 'Filter tickets list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'dc-tickets' ),
                'items_list_navigation' => _x( 'Tickets list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'dc-tickets' ),
                'items_list'            => _x( 'Tickets list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'dc-tickets' ),
            );

            $args = array(
                'labels'             => $labels,
                'public'             => false,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'ticket' ),
                'map_meta_cap'       => true,
                'capabilities'       => array(
                    'edit_post'              => 'edit_ticket',
                    'edit_posts'             => 'edit_tickets',
                    'edit_others_posts'      => 'edit_others_tickets',
                    'publish_posts'          => 'publish_tickets',
                    'read_post'              => 'read_ticket',
                    'read_private_posts'     => 'read_private_tickets',
                    'delete_post'            => 'delete_ticket',
                    'delete_private_posts'   => 'delete_private_tickets',
                    'delete_published_posts' => 'delete_published_tickets',
                    'delete_others_posts'    => 'delete_others_tickets',
                    'edit_private_posts'     => 'edit_private_tickets',
                    'edit_published_posts'   => 'edit_published_tickets',
                    'create_posts'           => 'edit_tickets',
                ),
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'menu_icon'          => 'dashicons-pressthis',
                'supports'           => array( 'title', 'author' ),
            );
         
            register_post_type( 'ticket', $args );
        }

        /**
         * This function will modify the custom column list for tickets in admin area
         *
         * @param Array $columns current columns
         * @return void
         */
        public function ticket_custom_columns_list( $columns ) {
            unset( $columns['author'] ); // to change the order for author column.
            unset( $columns['date']   ); // to change the order for date column.

            $columns['assigned_to']      = 'Assigned to';
            $columns['publication_name'] = 'Publication Name';
            $columns['rush_delivery']    = 'Rush Delivery';            
            $columns['status']           = 'Ticket Status';
            $columns['author']           = 'Author';
            $columns['date']             = 'Date';
                     
            return $columns;
        }

        public static function get_assigned_to($post_id) {
            $request_type               = get_field('request_type', $post_id);
            $assigned_to_field          = '';
            $sublevel_assigned_to_field = '';

            if ('graphic_design' === $request_type) {
                $assigned_to_field          = 'assigned_to_graphic_designers';
                $sublevel_assigned_to_field = 'assigned_to_others1';
            } else if ('website_update' === $request_type) {
                $assigned_to_field          = 'assigned_to_website_developers';
                $sublevel_assigned_to_field = 'assigned_to_others2';
            } else if ('virtual_tours' === $request_type) {
                $assigned_to_field          = 'assigned_to_virtual_tours';
                $sublevel_assigned_to_field = 'assigned_to_others3';
            } else if ('support' === $request_type) {
                $assigned_to_field          = 'assigned_to_support';
                $sublevel_assigned_to_field = 'assigned_to_others4';
            } 

            $assignment = [
                'lead' => [
                    'name'  => '',
                    'email' => '',
                    'role'  => '',
                ],
                'others' => [
                    'name'  => '',
                    'email' => '',
                    'role'  => '',
                ],
            ];

            if ( !empty($assigned_to_field) ) {        
                $assigned_to  = get_field($assigned_to_field, $post_id);
                $assigned_to_name = ! empty($assigned_to->display_name) ? $assigned_to->display_name : $assigned_to->user_login;

                $sublevel_usr = get_field($sublevel_assigned_to_field, $post_id);        
                $sub_assigned_to_name = ! empty($sublevel_usr->display_name) ? $sublevel_usr->display_name : $sublevel_usr->user_login;
                
                $assignment['lead']['name'] = $assigned_to_name;
                $assignment['lead']['email'] = $assigned_to->user_email;
                $assignment['lead']['role'] = sprintf('%s%s', $request_type, '_lead');
        
                if ( !empty( $sublevel_usr ) ) {
                    $assignment['others']['name'] = $sub_assigned_to_name;
                    $assignment['others']['email'] = $sublevel_usr->user_email;
                    $assignment['others']['role'] = sprintf('%s%s', $request_type, '_others');
                }
            }

            return $assignment;
        }

        /**
         * This function will set the values for custom column
         *
         * @param String  $column name for current column.
         * @param LongInt $post_id of current record.
         * @return void
         */
        public function ticket_custom_columns_values( $column, $post_id ) {
            if ( function_exists( 'get_field' ) ) {
                switch ( $column ) {
                    case 'assigned_to':
                        $assignment = self::get_assigned_to($post_id);

                        if ( !empty( $assignment['others']['name'] ) ) {                                                                   
                            $message = $assignment['others']['name'] . ' - ' .$assignment['others']['email'];
                    
                            echo $message;
                        } else {
                            echo '--None--';
                        }

                        break;
                    case 'publication_name':
                        $author_id = get_the_author_meta( 'ID' );
                        echo get_field('publication_name', "user_{$author_id}");
                        break;
                    case 'rush_delivery':
                        $rush_delivery = get_field( 'rush_delivery', $post_id );
                        $rush_delivery = 'yesrush' === $rush_delivery ? 'Yes' : 'No';

                        echo sprintf('%s', $rush_delivery);
                        break;                        
                    case 'status':
                        echo sprintf('%s', get_ticket_status( get_field( 'ticket_status', $post_id ) ));
                        break;
                }
            }
        }

        /**
         * This function will align admin rights to tickets.
         *
         * @return void
         */
        public function set_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the administrator role
            $role = get_role( 'administrator' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_ticket' );
            $role->add_cap( 'edit_tickets' );
            $role->add_cap( 'edit_others_tickets' );
            $role->add_cap( 'publish_tickets' );
            $role->add_cap( 'read_ticket' );
            $role->add_cap( 'read_private_tickets' );
            $role->add_cap( 'delete_ticket' );

            $role->add_cap( 'delete_private_tickets' );
            $role->add_cap( 'delete_published_tickets' );
            $role->add_cap( 'delete_others_tickets' );
            $role->add_cap( 'edit_private_tickets' );
            $role->add_cap( 'edit_published_tickets' );
        }

        /**
         * This function will align sub admin rights to tickets.
         *
         * @return void
         */
        public function set_sub_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the sub admin role
            $role = get_role( 'sub_admin' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_ticket' );
            $role->add_cap( 'edit_tickets' );
            $role->add_cap( 'edit_others_tickets' );
            $role->add_cap( 'publish_tickets' );
            $role->add_cap( 'read_ticket' );
            $role->add_cap( 'read_private_tickets' );

            $role->add_cap( 'delete_published_tickets' );
            $role->add_cap( 'edit_private_tickets' );
            $role->add_cap( 'edit_published_tickets' );
        }

        /**
         * This function will align publisher rights to tickets.
         *
         * @return void
         */
        public function set_publisher_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the publisher role
            $role = get_role( 'publisher' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_tickets' );
            $role->add_cap( 'edit_published_tickets' );
            $role->add_cap( 'publish_tickets' );
            $role->add_cap( 'read_ticket' );
            $role->add_cap( 'delete_published_tickets' );
        }

        /**
         * This function will align leader roles to tickets.
         *
         * @return void
         */
        public function set_lead_roles() {
            $leader1 = get_role( 'graphic_design_lead' );
            $leader2 = get_role( 'website_update_lead' );
            $leader3 = get_role( 'virtual_tours_lead' );
            $leader4 = get_role( 'support_lead' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $leader1->add_cap( 'edit_tickets' );
            $leader1->add_cap( 'edit_others_tickets' );
            $leader1->add_cap( 'edit_published_tickets' );
            $leader1->add_cap( 'read_ticket' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $leader2->add_cap( 'edit_tickets' );
            $leader2->add_cap( 'edit_others_tickets' );
            $leader2->add_cap( 'edit_published_tickets' );
            $leader2->add_cap( 'read_ticket' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $leader3->add_cap( 'edit_tickets' );
            $leader3->add_cap( 'edit_others_tickets' );
            $leader3->add_cap( 'edit_published_tickets' );
            $leader3->add_cap( 'read_ticket' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $leader4->add_cap( 'edit_tickets' );
            $leader4->add_cap( 'edit_others_tickets' );
            $leader4->add_cap( 'edit_published_tickets' );
            $leader4->add_cap( 'read_ticket' );
        }

        /**
         * This function will align other roles to tickets.
         *
         * @return void
         */
        public function set_other_roles() {
            $other1 = get_role( 'graphic_design_others' );
            $other2 = get_role( 'website_update_others' );
            $other3 = get_role( 'virtual_tours_others' );
            $other4 = get_role( 'support_others' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $other1->add_cap( 'edit_tickets' );
            $other1->add_cap( 'edit_others_tickets' );
            $other1->add_cap( 'edit_published_tickets' );
            $other1->add_cap( 'read_ticket' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $other2->add_cap( 'edit_tickets' );
            $other2->add_cap( 'edit_others_tickets' );
            $other2->add_cap( 'edit_published_tickets' );
            $other2->add_cap( 'read_ticket' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $other3->add_cap( 'edit_tickets' );
            $other3->add_cap( 'edit_others_tickets' );
            $other3->add_cap( 'edit_published_tickets' );
            $other3->add_cap( 'read_ticket' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $other4->add_cap( 'edit_tickets' );
            $other4->add_cap( 'edit_others_tickets' );
            $other4->add_cap( 'edit_published_tickets' );
            $other4->add_cap( 'read_ticket' );
        }

        /**
		 * This function will restrict the page access forcefully.
		 *
		 * @return void
		 */
		public function restrict_page_for_leaders() {
			global $pagenow;

			$arr = $this->leader_restricted_pages;

            $user = wp_get_current_user();

			if ( in_array( 'graphic_design_lead', (array) $user->roles ) || 
                 in_array( 'website_update_lead', (array) $user->roles ) ||
                 in_array( 'virtual_tours_lead', (array) $user->roles ) ||
                 in_array( 'support_lead', (array) $user->roles ) ) {
                if ( in_array( $pagenow, (array) $arr, true ) ) {
                    wp_die( 'Restricted Access! <pre>You are not authorized to access this page.</pre>' );
                }
            }
        }

        /**
		 * This function will restrict the page access forcefully.
		 *
		 * @return void
		 */
		public function restrict_page_for_others() {
			global $pagenow;

			$arr = $this->others_restricted_pages;

            $user = wp_get_current_user();

			if ( in_array( 'graphic_design_others', (array) $user->roles ) || 
                 in_array( 'website_update_others', (array) $user->roles ) ||
                 in_array( 'virtual_tours_others', (array) $user->roles ) ||
                 in_array( 'support_others', (array) $user->roles ) ) {
                if ( in_array( $pagenow, (array) $arr, true ) ) {
                    wp_die( 'Restricted Access! <pre>You are not authorized to access this page.</pre>' );
                }
            }
        }

        /**
		 * This function will remove add new buttons
		 *
		 * @return void
		 */
        public function hide_add_new_buttons() {
            $user = wp_get_current_user();

            if ( in_array( 'graphic_design_lead', (array) $user->roles ) || 
                 in_array( 'website_update_lead', (array) $user->roles ) ||
                 in_array( 'virtual_tours_lead', (array) $user->roles ) ||
                 in_array( 'support_lead', (array) $user->roles ) ) {
                    
                    // Hide sidebar link
                    global $submenu;

                    $submenu['edit.php?post_type=ticket'][10] = [];

                    echo '<style type="text/css">
                        .quicklinks ul.ab-top-menu li#wp-admin-bar-new-content, body.post-type-ticket .page-title-action { display:none; }
                        </style>';

            } else if ( in_array( 'graphic_design_others', (array) $user->roles ) || 
                    in_array( 'website_update_others', (array) $user->roles ) ||
                    in_array( 'virtual_tours_others', (array) $user->roles ) ||
                    in_array( 'support_others', (array) $user->roles ) ) {
                    
                    // Hide sidebar link
                    global $submenu;

                    $submenu['edit.php?post_type=ticket'][10] = [];

                    echo '<style type="text/css">
                        .quicklinks ul.ab-top-menu li#wp-admin-bar-new-content, body.post-type-ticket .page-title-action { display:none; }
                        </style>';
            }
        }

        /**
         * Works when publisher logged in: only displays custom post type owned by publisher.
         *
         * @param Object $wp_query from wordpress
         * @return void
         */
        public function display_publisher_posts_only( $wp_query ) {
            global $pagenow;
            
            // only in admin area.
            if ( is_admin() ) {
                $user = wp_get_current_user();
                if ( in_array( 'publisher', (array) $user->roles ) ) {
                    //The user has the "publisher" role
                    if ( 'edit.php' == $pagenow ) {
                        $wp_query->set( 'author', $user->ID );
                    }

                    // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                    add_filter('views_edit-ticket', array( $this, 'fix_ticket_counts' ) );
                }
            }
        }

        /**
         * Works when leaders are logged in: only displays custom post type assigned to leaders.
         *
         * @param Object $wp_query from wordpress
         * @return void
         */
        public function display_leaders_posts_only( $wp_query ) {
            global $pagenow;

            // only in admin area.
            if ( is_admin() ) {
                $user = wp_get_current_user();
                if ( in_array( 'graphic_design_lead', (array) $user->roles ) ) {
                    //The user has the "graphic_design_lead" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_graphic_designers',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );                    

                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->leader_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_leader_ticket_counts' ) );
                    }
                } else if ( in_array( 'website_update_lead', (array) $user->roles ) ) {
                    //The user has the "website_update_lead" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_website_developers',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );
                        
                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->leader_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_leader_ticket_counts' ) );
                    }
                } else if ( in_array( 'virtual_tours_lead', (array) $user->roles ) ) {
                    //The user has the "virtual_tours_lead" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_virtual_tours',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );
                        
                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->leader_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_leader_ticket_counts' ) );
                    }
                } else if ( in_array( 'support_lead', (array) $user->roles ) ) {
                    //The user has the "support_lead" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_support',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );
                        
                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->leader_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_leader_ticket_counts' ) );
                    }
                }
            }
        }

        /**
         * Works when others are logged in: only displays custom post type assigned to others.
         *
         * @param Object $wp_query from wordpress
         * @return void
         */
        public function display_others_posts_only( $wp_query ) {
            global $pagenow;

            // only in admin area.
            if ( is_admin() ) {
                $user = wp_get_current_user();
                if ( in_array( 'graphic_design_others', (array) $user->roles ) ) {
                    //The user has the "graphic_design_others" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_others1',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );                    

                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->others_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_other_ticket_counts' ) );
                    }
                } else if ( in_array( 'website_update_others', (array) $user->roles ) ) {
                    //The user has the "website_update_others" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_others2',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );
                        
                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->others_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_other_ticket_counts' ) );
                    }
                } else if ( in_array( 'virtual_tours_others', (array) $user->roles ) ) {
                    //The user has the "virtual_tours_others" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_others3',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );
                        
                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->others_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_other_ticket_counts' ) );
                    }
                } else if ( in_array( 'support_others', (array) $user->roles ) ) {
                    //The user has the "support_others" role
                    if ( 'edit.php' == $pagenow ) {
                        $meta_query = array(
                            array(
                                'key'     => 'assigned_to_others4',
                                'value'   => $user->ID,
                                'compare' => '=',
                            ),
                        );
                        
                        // assign it to class variable, this is important to avoid having duplicate code
                        $this->others_meta_query = $meta_query;

                        $wp_query->set( 'meta_query', $meta_query );

                        // https://www.collectiveray.com/show-only-posts-media-owned-logged-in-wordpress-user
                        add_filter('views_edit-ticket', array( $this, 'fix_other_ticket_counts' ) );
                    }
                }
            }
        }

        /**
         * Works when publisher logged in: update number of counts on links and status.
         *
         * @param Array $views from wordpress
         * @return Array
         */
        public function fix_ticket_counts( $views ) {
            global $current_user, $wp_query;

            unset($views['mine']);

            $types = array(
                array( 'status' =>  NULL ),        
                array( 'status' => 'publish' ),            
                array( 'status' => 'draft' ),            
                array( 'status' => 'pending' ),            
                array( 'status' => 'trash' )            
            );

            foreach( $types as $type ) {
                $query = array(            
                    'author'      => $current_user->ID,            
                    'post_type'   => 'ticket',            
                    'post_status' => $type['status']            
                );

                $result = new \WP_Query( $query );

                if ( $type['status'] == NULL ):            
                    $class = ( $wp_query->query_vars['post_status'] == NULL ) ? ' class="current"' : '';
            
                    $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),            
                                    admin_url('edit.php?post_type=ticket'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'publish' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'publish' ) ? ' class="current"' : '';
            
                    $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),            
                                        admin_url('edit.php?post_status=publish&post_type=ticket'),
                                        $result->found_posts);
            
                elseif ( $type['status'] == 'draft' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'draft' ) ? ' class="current"' : '';
            
                    $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),            
                                    admin_url('edit.php?post_status=draft&post_type=ticket'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'pending' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'pending' ) ? ' class="current"' : '';
            
                    $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),            
                                        admin_url('edit.php?post_status=pending&post_type=ticket'),            
                                        $result->found_posts);
            
                elseif( $type['status'] == 'trash' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'trash' ) ? ' class="current"' : '';

                    if ( in_array( 'publisher', (array) $current_user->roles ) ) {
                        unset( $views['trash'] );
                    } else {
                        $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),            
                            admin_url('edit.php?post_status=trash&post_type=ticket'),            
                            $result->found_posts);
                    }
            
                endif;            
            }
            
            return $views;
        }

        /**
         * Works when leaders logged in: update number of counts on links and status.
         *
         * @param Array $views from wordpress
         * @return Array
         */
        public function fix_leader_ticket_counts( $views ) {
            global $current_user, $wp_query;

            unset($views['mine']);

            $types = array(
                array( 'status' =>  NULL ),        
                array( 'status' => 'publish' ),            
                array( 'status' => 'draft' ),            
                array( 'status' => 'pending' ),            
                array( 'status' => 'trash' )            
            );

            $meta_query = $this->leader_meta_query;

            foreach( $types as $type ) {
                $query = array(                                
                    'meta_query'  => $meta_query,            
                    'post_type'   => 'ticket',            
                    'post_status' => $type['status']            
                );

                $result = new \WP_Query( $query );

                if ( $type['status'] == NULL ):            
                    $class = ( $wp_query->query_vars['post_status'] == NULL ) ? ' class="current"' : '';
            
                    $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),            
                                    admin_url('edit.php?post_type=ticket'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'publish' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'publish' ) ? ' class="current"' : '';
            
                    $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),            
                                        admin_url('edit.php?post_status=publish&post_type=ticket'),
                                        $result->found_posts);
            
                elseif ( $type['status'] == 'draft' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'draft' ) ? ' class="current"' : '';
            
                    $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),            
                                    admin_url('edit.php?post_status=draft&post_type=ticket'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'pending' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'pending' ) ? ' class="current"' : '';
            
                    $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),            
                                        admin_url('edit.php?post_status=pending&post_type=ticket'),            
                                        $result->found_posts);
            
                elseif( $type['status'] == 'trash' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'trash' ) ? ' class="current"' : '';
            
                    $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),            
                                        admin_url('edit.php?post_status=trash&post_type=ticket'),            
                                        $result->found_posts);
            
                endif;            
            }
            
            return $views;
        }

        /**
         * Works when others logged in: update number of counts on links and status.
         *
         * @param Array $views from wordpress
         * @return Array
         */
        public function fix_other_ticket_counts( $views ) {
            global $current_user, $wp_query;

            unset($views['mine']);

            $types = array(
                array( 'status' =>  NULL ),        
                array( 'status' => 'publish' ),            
                array( 'status' => 'draft' ),            
                array( 'status' => 'pending' ),            
                array( 'status' => 'trash' )            
            );

            $meta_query = $this->others_meta_query;

            foreach( $types as $type ) {
                $query = array(                                
                    'meta_query'  => $meta_query,            
                    'post_type'   => 'ticket',            
                    'post_status' => $type['status']            
                );

                $result = new \WP_Query( $query );

                if ( $type['status'] == NULL ):            
                    $class = ( $wp_query->query_vars['post_status'] == NULL ) ? ' class="current"' : '';
            
                    $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),            
                                    admin_url('edit.php?post_type=ticket'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'publish' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'publish' ) ? ' class="current"' : '';
            
                    $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),            
                                        admin_url('edit.php?post_status=publish&post_type=ticket'),
                                        $result->found_posts);
            
                elseif ( $type['status'] == 'draft' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'draft' ) ? ' class="current"' : '';
            
                    $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),            
                                    admin_url('edit.php?post_status=draft&post_type=ticket'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'pending' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'pending' ) ? ' class="current"' : '';
            
                    $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),            
                                        admin_url('edit.php?post_status=pending&post_type=ticket'),            
                                        $result->found_posts);
            
                elseif( $type['status'] == 'trash' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'trash' ) ? ' class="current"' : '';
            
                    $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),            
                                        admin_url('edit.php?post_status=trash&post_type=ticket'),            
                                        $result->found_posts);
            
                endif;            
            }
            
            return $views;
        }

        public function auto_assign( $post_id ) {
            if ( 'ticket' !== get_post_type( $post_id ) ) {
                return;
            }

            $request_type = get_field('request_type', $post_id);

            if ('graphic_design' !== $request_type) {
                return;
            }

            $assigned_to_field          = 'assigned_to_graphic_designers';
            $sublevel_assigned_to_field = 'assigned_to_others1';

            $assigned_to  = get_field($assigned_to_field, $post_id);
            $sublevel_usr = get_field($sublevel_assigned_to_field, $post_id);                
                
            // only when fields are not saved.
            if ( empty( $assigned_to ) && empty( $sublevel_usr ) ) {
                $assignment = \DiviChild\Classes\Inc\DCAssignmentInfo::get_auto_assigned_to();

                if ( ! empty( $assignment['lead']['user'] ) && ! empty( $assignment['other']['user'] ) ) {
                    update_field($assigned_to_field, $assignment['lead']['user'], $post_id);
                    update_field($sublevel_assigned_to_field, $assignment['other']['user'], $post_id);
                }
            }
        }
    }
}

new DCTickets();
