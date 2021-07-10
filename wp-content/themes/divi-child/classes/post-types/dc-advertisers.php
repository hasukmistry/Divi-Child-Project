<?php

namespace DiviChild\Classes\PostTypes;

// Creates class if not exists.
if ( ! class_exists('DCAdvertisers') ) {
    class DCAdvertisers {
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

            $post_type = 'advertiser';
            add_filter( "manage_{$post_type}_posts_columns", array( $this, 'advertiser_custom_columns_list' ) );
            add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'advertiser_custom_column_values' ), 10, 2 );
        }

        /**
         * This will add custom post type to wordpress.
         *
         * @return void
         */
        public function create_post_type() {
            // https://developer.wordpress.org/reference/functions/register_post_type/

            $labels = array(
                'name'                  => _x( 'Advertisers', 'Post type general name', 'dc-advertisers' ),
                'singular_name'         => _x( 'Advertiser', 'Post type singular name', 'dc-advertisers' ),
                'menu_name'             => _x( 'Advertisers', 'Admin Menu text', 'dc-advertisers' ),
                'name_admin_bar'        => _x( 'Advertiser', 'Add New on Toolbar', 'dc-advertisers' ),
                'add_new'               => __( 'Add New', 'dc-advertisers' ),
                'add_new_item'          => __( 'Add New Advertiser', 'dc-advertisers' ),
                'new_item'              => __( 'New Advertiser', 'dc-advertisers' ),
                'edit_item'             => __( 'Edit Advertiser', 'dc-advertisers' ),
                'view_item'             => __( 'View Advertiser', 'dc-advertisers' ),
                'view_items'            => __( 'View Advertisers', 'dc-advertisers' ),
                'all_items'             => __( 'All Advertisers', 'dc-advertisers' ),
                'search_items'          => __( 'Search Advertisers', 'dc-advertisers' ),
                'parent_item_colon'     => __( 'Parent Advertisers:', 'dc-advertisers' ),
                'not_found'             => __( 'No advertisers found.', 'dc-advertisers' ),
                'not_found_in_trash'    => __( 'No advertisers found in Trash.', 'dc-advertisers' ),
                'featured_image'        => _x( 'Advertiser\'s Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dc-advertisers' ),
                'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dc-advertisers' ),
                'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dc-advertisers' ),
                'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'dc-advertisers' ),
                'archives'              => _x( 'Advertiser archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'dc-advertisers' ),
                'insert_into_item'      => _x( 'Insert into advertiser', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'dc-advertisers' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this advertiser', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'dc-advertisers' ),
                'filter_items_list'     => _x( 'Filter advertisers list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'dc-advertisers' ),
                'items_list_navigation' => _x( 'Advertisers list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'dc-advertisers' ),
                'items_list'            => _x( 'Advertisers list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'dc-advertisers' ),
            );

            $args = array(
                'labels'             => $labels,
                'public'             => false,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'advertiser' ),
                'map_meta_cap'       => true,
                'capabilities'       => array(
                    'edit_post'              => 'edit_advertiser',
                    'edit_posts'             => 'edit_advertisers',
                    'edit_others_posts'      => 'edit_others_advertisers',
                    'publish_posts'          => 'publish_advertisers',
                    'read_post'              => 'read_advertiser',
                    'read_private_posts'     => 'read_private_advertisers',
                    'delete_post'            => 'delete_advertiser',
                    'delete_private_posts'   => 'delete_private_advertisers',
                    'delete_published_posts' => 'delete_published_advertisers',
                    'delete_others_posts'    => 'delete_others_advertisers',
                    'edit_private_posts'     => 'edit_private_advertisers',
                    'edit_published_posts'   => 'edit_published_advertisers',
                    'create_posts'           => 'edit_advertisers',
                ),
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'menu_icon'          => 'dashicons-chart-area',
                'supports'           => array( 'title', 'author' ),
            );
         
            register_post_type( 'advertiser', $args );
        }

        /**
         * This function will modify the column list for advertisers in admin area
         *
         * @param Array $columns current columns
         * @return void
         */
        public function advertiser_custom_columns_list( $columns ) {
            unset( $columns['author'] ); // to change the order for author column.
            unset( $columns['date']   ); // to change the order for date column.

            $columns['artwork_by']     = 'Artwork By';
            $columns['issue']          = 'Issue';
            $columns['ad_size']        = 'Ad Size';
            $columns['payment_status'] = 'Payment Status';
            $columns['date']           = 'Date Created';
                     
            return $columns;
        }

        /**
         * This function will set the values for custom column
         *
         * @param String  $column name for current column.
         * @param LongInt $post_id of current record.
         * @return void
         */
        public function advertiser_custom_column_values( $column, $post_id ) {
            if ( function_exists( 'get_field' ) ) {
                switch ( $column ) {
                    case 'artwork_by':
                        $artwork_by = get_field( "artwork_by", $post_id );
                        $artwork_by_value = '';
                        if ( 'advertiser' === $artwork_by ) {
                            $artwork_by_value = 'Advertiser';
                        } elseif ( 'central' === $artwork_by ) {
                            $artwork_by_value = 'Central';
                        }
                        echo ! empty( $artwork_by_value ) ? $artwork_by_value : '—';
                        break;
                    case 'issue':
                        $issue_advertising_in = get_field( "issue_advertising_in", $post_id );
                        if ( null !== $issue_advertising_in ) {
                            echo sprintf( __('<a href="%s">%s</a>'),            
                            get_edit_post_link( $issue_advertising_in ),
                            get_the_title( $issue_advertising_in ) );
                        } else {
                            echo '—';
                        }
                        // echo ! empty( $vendor_email ) ? $vendor_email : '—';
                        break;
                    case 'ad_size':
                        echo sprintf('%s', get_advertiser_ad_size( get_field( 'ad_size', $post_id ) ));
                        break;
                    case 'payment_status':
                        echo sprintf('%s', get_advertiser_payment_status( get_field( 'payment_status', $post_id ) ));
                        break;                                                                
                }
            }
        }

        /**
         * This function will align admin rights to advertiser.
         *
         * @return void
         */
        public function set_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the administrator role
            $role = get_role( 'administrator' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_advertiser' );
            $role->add_cap( 'edit_advertisers' );
            $role->add_cap( 'edit_others_advertisers' );
            $role->add_cap( 'publish_advertisers' );
            $role->add_cap( 'read_advertiser' );
            $role->add_cap( 'read_private_advertisers' );
            $role->add_cap( 'delete_advertiser' );

            $role->add_cap( 'delete_private_advertisers' );
            $role->add_cap( 'delete_published_advertisers' );
            $role->add_cap( 'delete_others_advertisers' );
            $role->add_cap( 'edit_private_advertisers' );
            $role->add_cap( 'edit_published_advertisers' );
        }

        /**
         * This function will align sub admin rights to advertiser.
         *
         * @return void
         */
        public function set_sub_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the sub admin role
            $role = get_role( 'sub_admin' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_advertiser' );
            $role->add_cap( 'edit_advertisers' );
            $role->add_cap( 'edit_others_advertisers' );
            $role->add_cap( 'publish_advertisers' );
            $role->add_cap( 'read_advertiser' );
            $role->add_cap( 'read_private_advertisers' );

            $role->add_cap( 'delete_published_advertisers' );
            $role->add_cap( 'edit_private_advertisers' );
            $role->add_cap( 'edit_published_advertisers' );
        }

        /**
         * This function will align publisher rights to advertiser.
         *
         * @return void
         */
        public function set_publisher_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the publisher role
            $role = get_role( 'publisher' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_advertisers' );
            $role->add_cap( 'edit_published_advertisers' );
            $role->add_cap( 'publish_advertisers' );
            $role->add_cap( 'read_advertiser' );
            $role->add_cap( 'delete_published_advertisers' );
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
                    add_filter('views_edit-advertiser', array( $this, 'fix_advertiser_counts' ) );
                }
            }
        }

        /**
         * Works when publisher logged in: update number of counts on links and status.
         *
         * @param Array $views from wordpress
         * @return Array
         */
        public function fix_advertiser_counts( $views ) {
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
                    'post_type'   => 'advertiser',            
                    'post_status' => $type['status']            
                );

                $result = new \WP_Query( $query );

                if ( $type['status'] == NULL ):            
                    $class = ( $wp_query->query_vars['post_status'] == NULL ) ? ' class="current"' : '';
            
                    $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),            
                                    admin_url('edit.php?post_type=advertiser'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'publish' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'publish' ) ? ' class="current"' : '';
            
                    $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),            
                                        admin_url('edit.php?post_status=publish&post_type=advertiser'),
                                        $result->found_posts);
            
                elseif ( $type['status'] == 'draft' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'draft' ) ? ' class="current"' : '';
            
                    $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),            
                                    admin_url('edit.php?post_status=draft&post_type=advertiser'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'pending' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'pending' ) ? ' class="current"' : '';
            
                    $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),            
                                        admin_url('edit.php?post_status=pending&post_type=advertiser'),            
                                        $result->found_posts);
            
                elseif( $type['status'] == 'trash' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'trash' ) ? ' class="current"' : '';
            
                    $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),            
                                        admin_url('edit.php?post_status=trash&post_type=advertiser'),            
                                        $result->found_posts);
            
                endif;            
            }
            
            return $views;
        }
    }
}

new DCAdvertisers();
