<?php

namespace DiviChild\Classes\PostTypes;

// Creates class if not exists.
if ( ! class_exists('DCIssues') ) {
    class DCIssues {
        /**
         * Secret key to encode and decode issue slug
         *
         * @var string
         */
        private $token_key = 'DCQA;kTUw<4Bt5GF/ZmvWcz7emfX21e2c8ad532cCIssues';

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

            // only for publishers.
            add_action( 'pre_get_posts', array( $this, 'display_publisher_posts_only') );

            // new custom token based slug for issues.
            add_action( 'save_post', array( $this, 'update_issues_callback') , 10, 3 );

            $post_type = 'issue';
            add_filter( "manage_{$post_type}_posts_columns", array( $this, "{$post_type}_custom_columns_list" ) );
            add_action( "manage_{$post_type}_posts_custom_column", array( $this, "{$post_type}_custom_columns_values" ), 10, 2 );


            // sorting columns
            add_filter(
                "manage_edit-{$post_type}_sortable_columns", 
                function( $columns ) { 
                   $columns['publication_name'] = 'publication_name';
                   $columns['status'] = 'status';
                   return $columns;
                }
            );

            add_action( 'pre_get_posts', array( $this, 'issue_meta_sorting' ) );

            add_action( 'admin_enqueue_scripts', array( &$this, 'add_scripts' ), 100, 1 );
        }

        /**
         * This will add custom post type to wordpress.
         *
         * @return void
         */
        public function create_post_type() {
            // https://developer.wordpress.org/reference/functions/register_post_type/

            $labels = array(
                'name'                  => _x( 'Issues', 'Post type general name', 'dc-issues' ),
                'singular_name'         => _x( 'Issue', 'Post type singular name', 'dc-issues' ),
                'menu_name'             => _x( 'Issues', 'Admin Menu text', 'dc-issues' ),
                'name_admin_bar'        => _x( 'Issue', 'Add New on Toolbar', 'dc-issues' ),
                'add_new'               => __( 'Add New', 'dc-issues' ),
                'add_new_item'          => __( 'Add New Issue', 'dc-issues' ),
                'new_item'              => __( 'New Issue', 'dc-issues' ),
                'edit_item'             => __( 'Edit Issue', 'dc-issues' ),
                'view_item'             => __( 'View Issue', 'dc-issues' ),
                'view_items'            => __( 'View Issues', 'dc-issues' ),
                'all_items'             => __( 'All Issues', 'dc-issues' ),
                'search_items'          => __( 'Search Issues', 'dc-issues' ),
                'parent_item_colon'     => __( 'Parent Issues:', 'dc-issues' ),
                'not_found'             => __( 'No issues found.', 'dc-issues' ),
                'not_found_in_trash'    => __( 'No issues found in Trash.', 'dc-issues' ),
                'featured_image'        => _x( 'Issue\'s Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dc-issues' ),
                'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dc-issues' ),
                'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dc-issues' ),
                'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'dc-issues' ),
                'archives'              => _x( 'Issue archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'dc-issues' ),
                'insert_into_item'      => _x( 'Insert into issue', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'dc-issues' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this issue', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'dc-issues' ),
                'filter_items_list'     => _x( 'Filter issues list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'dc-issues' ),
                'items_list_navigation' => _x( 'Issues list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'dc-issues' ),
                'items_list'            => _x( 'Issues list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'dc-issues' ),
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'issue' ),
                'map_meta_cap'       => true,
                'capabilities'       => array(
                    'edit_post'              => 'edit_issue',
                    'edit_posts'             => 'edit_issues',
                    'edit_others_posts'      => 'edit_others_issues',
                    'publish_posts'          => 'publish_issues',
                    'read_post'              => 'read_issue',
                    'read_private_posts'     => 'read_private_issues',
                    'delete_post'            => 'delete_issue',
                    'delete_private_posts'   => 'delete_private_issues',
                    'delete_published_posts' => 'delete_published_issues',
                    'delete_others_posts'    => 'delete_others_issues',
                    'edit_private_posts'     => 'edit_private_issues',
                    'edit_published_posts'   => 'edit_published_issues',
                    'create_posts'           => 'edit_issues',
                ),
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'menu_icon'          => 'dashicons-book',
                // 'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
                'supports'           => array( 'title', 'author' ),
            );
         
            register_post_type( 'issue', $args );
        }

        /**
         * This function will modify the custom column list for issues in admin area
         *
         * @param Array $columns current columns
         * @return void
         */
        public function issue_custom_columns_list( $columns ) {
            unset( $columns['author'] ); // to change the order for author column.
            unset( $columns['date']   ); // to change the order for date column.

            $columns['assigned_to']      = 'Assigned to';
            $columns['publication_name'] = 'Publication Name';
            $columns['status']           = 'Issue Status';
            $columns['author']           = 'Author';
            $columns['date']             = 'Date';
                     
            return $columns;
        }

        public static function get_assigned_to($post_id) {
            $assignment = [
                'name' => '',
                'email' => '',
            ];
            
            $assigned_to_field  = 'assigned_to';
            $assigned_to = get_field($assigned_to_field, $post_id);

            $assignment['name'] = ! empty($assigned_to->display_name) ? $assigned_to->display_name : $assigned_to->user_login;
            $assignment['email'] = $assigned_to->user_email;

            return $assignment;
        }

        /**
         * This function will set the values for custom column
         *
         * @param String  $column name for current column.
         * @param LongInt $post_id of current record.
         * @return void
         */
        public function issue_custom_columns_values( $column, $post_id ) {
            if ( function_exists( 'get_field' ) ) {
                switch ( $column ) {
                    case 'assigned_to':
                        $assignment = self::get_assigned_to($post_id);

                        if ( !empty( $assignment['name'] ) ) {                                                                   
                            $message = $assignment['name'] . ' - ' .$assignment['email'];
                    
                            echo $message;
                        } else {
                            echo '--None--';
                        }
                        break;
                    case 'publication_name':
                        $author_id = get_the_author_meta( 'ID' );
                        echo get_field('publication_name', "user_{$author_id}");
                        break;
                    case 'status':
                        echo sprintf('%s', get_issue_status( get_field( 'issue_status', $post_id ) ));
                        break;
                }
            }
        }

        /**
         * This function will align admin rights to issue.
         *
         * @return void
         */
        public function set_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the administrator role
            $role = get_role( 'administrator' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_issue' );
            $role->add_cap( 'edit_issues' );
            $role->add_cap( 'edit_others_issues' );
            $role->add_cap( 'publish_issues' );
            $role->add_cap( 'read_issue' );
            $role->add_cap( 'read_private_issues' );
            $role->add_cap( 'delete_issue' );

            $role->add_cap( 'delete_private_issues' );
            $role->add_cap( 'delete_published_issues' );
            $role->add_cap( 'delete_others_issues' );
            $role->add_cap( 'edit_private_issues' );
            $role->add_cap( 'edit_published_issues' );
        }

        /**
         * This function will align sub admin rights to issue.
         *
         * @return void
         */
        public function set_sub_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the sub admin role
            $role = get_role( 'sub_admin' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_issue' );
            $role->add_cap( 'edit_issues' );
            $role->add_cap( 'edit_others_issues' );
            $role->add_cap( 'publish_issues' );
            $role->add_cap( 'read_issue' );
            $role->add_cap( 'read_private_issues' );

            $role->add_cap( 'delete_published_issues' );
            $role->add_cap( 'edit_private_issues' );
            $role->add_cap( 'edit_published_issues' );
        }

        /**
         * This function will align publisher rights to issue.
         *
         * @return void
         */
        public function set_publisher_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the publisher role
            $role = get_role( 'publisher' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_issues' );
            $role->add_cap( 'edit_published_issues' );
            $role->add_cap( 'publish_issues' );
            $role->add_cap( 'read_issue' );
            $role->add_cap( 'delete_published_issues' );
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
                    add_filter('views_edit-issue', array( $this, 'fix_issue_counts' ) );
                }
            }
        }

        /**
         * Works when publisher logged in: update number of counts on links and status.
         *
         * @param Array $views from wordpress
         * @return Array
         */
        public function fix_issue_counts( $views ) {
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
                    'post_type'   => 'issue',            
                    'post_status' => $type['status']            
                );

                $result = new \WP_Query( $query );

                if ( $type['status'] == NULL ):            
                    $class = ( $wp_query->query_vars['post_status'] == NULL ) ? ' class="current"' : '';
            
                    $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),            
                                    admin_url('edit.php?post_type=issue'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'publish' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'publish' ) ? ' class="current"' : '';
            
                    $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),            
                                        admin_url('edit.php?post_status=publish&post_type=issue'),
                                        $result->found_posts);
            
                elseif ( $type['status'] == 'draft' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'draft' ) ? ' class="current"' : '';
            
                    $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),            
                                    admin_url('edit.php?post_status=draft&post_type=issue'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'pending' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'pending' ) ? ' class="current"' : '';
            
                    $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),            
                                        admin_url('edit.php?post_status=pending&post_type=issue'),            
                                        $result->found_posts);
            
                elseif( $type['status'] == 'trash' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'trash' ) ? ' class="current"' : '';
            
                    $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),            
                                        admin_url('edit.php?post_status=trash&post_type=issue'),            
                                        $result->found_posts);
            
                endif;            
            }
            
            return $views;
        }

        /**
         * This function will generate hash based token slug.
         *
         * @param LongInt $post_ID of post.
         * @param Object  $post object of issue.
         * @param Boolean $update update or new.
         * @return void
         */
        public function update_issues_callback( $post_ID, $post, $update ) {
            // allow 'publish', 'draft', 'future'
            if ($post->post_type !== 'issue' || $post->post_status === 'auto-draft')
                return;

            // only change slug when the post is created (both dates are equal)
            if ( $post->post_date_gmt !== $post->post_modified_gmt )
                return;

            if ( ! function_exists( 'hash_hmac' ) ) {
                return;
            }

            $new_slug = hash_hmac( 'sha256', $post->post_name, $this->token_key );

            if ( $new_slug === '' )
                return; // if empty, do nothing, will use post_name is slug

            // unhook this function to prevent infinite looping
            remove_action( 'save_post', array( $this, 'update_issues_callback'), 10, 3 );

            // does update here
            // update the post slug (WP handles unique post slug)
            wp_update_post( array(
                'ID' => $post_ID,
                'post_name' => $new_slug
            ));

            // re-hook this function
            add_action( 'save_post', array( $this, 'update_issues_callback'), 10, 3 );
        }

        public function issue_meta_sorting( $query ) {
            if ( $query->is_main_query() && 'issue' === $query->query['post_type'] && $query->get( 'orderby' ) === 'status' ) { 
                $query->set( 'meta_key', 'issue_status' /* Post meta field name of status */ );
                $query->set( 'orderby', 'meta_value' );
            }
        }

        public function add_scripts() {
            $screen = get_current_screen();
            
            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'issue' === $screen->id ) {
                    $theme_css = '/assets/css/dc-admin-issue.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }
    }
}

new DCIssues();
