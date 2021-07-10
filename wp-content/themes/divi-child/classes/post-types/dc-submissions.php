<?php

namespace DiviChild\Classes\PostTypes;

// Creates class if not exists.
if ( ! class_exists('DCSubmissions') ) {
    class DCSubmissions {
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

            $post_type = 'submission';
            add_filter( "manage_{$post_type}_posts_columns", array( $this, 'submission_custom_columns_list' ) );
            add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'submission_custom_column_values' ), 10, 2 );

            // add back button
            add_action( 'admin_head-post.php', array( $this, 'add_back_button' ) );
        }

        /**
         * This will add custom post type to wordpress.
         *
         * @return void
         */
        public function create_post_type() {
            // https://developer.wordpress.org/reference/functions/register_post_type/

            $labels = array(
                'name'                  => _x( 'Submissions', 'Post type general name', 'dc-submissions' ),
                'singular_name'         => _x( 'Submission', 'Post type singular name', 'dc-submissions' ),
                'menu_name'             => _x( 'Submissions', 'Admin Menu text', 'dc-submissions' ),
                'name_admin_bar'        => _x( 'Submission', 'Add New on Toolbar', 'dc-submissions' ),
                'add_new'               => __( 'Add New', 'dc-submissions' ),
                'add_new_item'          => __( 'Add New Submission', 'dc-submissions' ),
                'new_item'              => __( 'New Submission', 'dc-submissions' ),
                'edit_item'             => __( 'Edit Submission', 'dc-submissions' ),
                'view_item'             => __( 'View Submission', 'dc-submissions' ),
                'view_items'            => __( 'View Submissions', 'dc-submissions' ),
                'all_items'             => __( 'All Submissions', 'dc-submissions' ),
                'search_items'          => __( 'Search Submissions', 'dc-submissions' ),
                'parent_item_colon'     => __( 'Parent Submissions:', 'dc-submissions' ),
                'not_found'             => __( 'No submissions found.', 'dc-submissions' ),
                'not_found_in_trash'    => __( 'No submissions found in Trash.', 'dc-submissions' ),
                'featured_image'        => _x( 'Submission\'s Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dc-submissions' ),
                'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dc-submissions' ),
                'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dc-submissions' ),
                'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'dc-submissions' ),
                'archives'              => _x( 'Submission archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'dc-submissions' ),
                'insert_into_item'      => _x( 'Insert into submission', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'dc-submissions' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this submission', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'dc-submissions' ),
                'filter_items_list'     => _x( 'Filter submissions list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'dc-submissions' ),
                'items_list_navigation' => _x( 'Submissions list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'dc-submissions' ),
                'items_list'            => _x( 'Submissions list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'dc-submissions' ),
            );

            $args = array(
                'labels'             => $labels,
                'public'             => false,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => false,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'submission' ),
                'map_meta_cap'       => true,
                'capabilities'       => array(
                    'edit_post'              => 'edit_submission',
                    'edit_posts'             => 'edit_submissions',
                    'edit_others_posts'      => 'edit_others_submissions',
                    'publish_posts'          => 'publish_submissions',
                    'read_post'              => 'read_submission',
                    'read_private_posts'     => 'read_private_submissions',
                    'delete_post'            => 'delete_submission',
                    'delete_private_posts'   => 'delete_private_submissions',
                    'delete_published_posts' => 'delete_published_submissions',
                    'delete_others_posts'    => 'delete_others_submissions',
                    'edit_private_posts'     => 'edit_private_submissions',
                    'edit_published_posts'   => 'edit_published_submissions',
                    'create_posts'           => 'edit_submissions',
                ),
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'menu_icon'          => 'dashicons-chart-area',
                'supports'           => array( 'title', 'author' ),
            );
         
            register_post_type( 'submission', $args );
        }

        /**
         * This function will modify the column list for submissions in admin area
         *
         * @param Array $columns current columns
         * @return void
         */
        public function submission_custom_columns_list( $columns ) {
            unset( $columns['author'] ); // to change the order for author column.
            unset( $columns['date']   ); // to change the order for date column.

            $columns['submission_status'] = 'Submission Status';
            $columns['date']              = 'Date Created';
                     
            return $columns;
        }

        /**
         * This function will set the values for custom column
         *
         * @param String  $column name for current column.
         * @param LongInt $post_id of current record.
         * @return void
         */
        public function submission_custom_column_values( $column, $post_id ) {
            if ( function_exists( 'get_field' ) ) {
                switch ( $column ) {
                    case 'submission_status':
                        echo sprintf('%s', get_submission_status( get_field( 'submission_status', $post_id ) ));
                        break;                                                                
                }
            }
        }

        /**
         * This function will align admin rights to submission.
         *
         * @return void
         */
        public function set_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the administrator role
            $role = get_role( 'administrator' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_submission' );
            $role->add_cap( 'edit_submissions' );
            $role->add_cap( 'edit_others_submissions' );
            $role->add_cap( 'publish_submissions' );
            $role->add_cap( 'read_submission' );
            $role->add_cap( 'read_private_submissions' );
            $role->add_cap( 'delete_submission' );

            $role->add_cap( 'delete_private_submissions' );
            $role->add_cap( 'delete_published_submissions' );
            $role->add_cap( 'delete_others_submissions' );
            $role->add_cap( 'edit_private_submissions' );
            $role->add_cap( 'edit_published_submissions' );
        }

        /**
         * This function will align sub admin rights to submission.
         *
         * @return void
         */
        public function set_sub_admin_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the sub admin role
            $role = get_role( 'sub_admin' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_submission' );
            $role->add_cap( 'edit_submissions' );
            $role->add_cap( 'edit_others_submissions' );
            $role->add_cap( 'publish_submissions' );
            $role->add_cap( 'read_submission' );
            $role->add_cap( 'read_private_submissions' );

            $role->add_cap( 'delete_published_submissions' );
            $role->add_cap( 'edit_private_submissions' );
            $role->add_cap( 'edit_published_submissions' );
        }

        /**
         * This function will align publisher rights to submission.
         *
         * @return void
         */
        public function set_publisher_rights() {
            // https://codex.wordpress.org/Function_Reference/add_cap

            // gets the publisher role
            $role = get_role( 'publisher' );

            // https://wordpress.org/support/article/roles-and-capabilities/
            $role->add_cap( 'edit_submissions' );
            $role->add_cap( 'edit_published_submissions' );
            $role->add_cap( 'publish_submissions' );
            $role->add_cap( 'read_submission' );
            $role->add_cap( 'delete_published_submissions' );
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
                    add_filter('views_edit-submission', array( $this, 'fix_submission_counts' ) );
                }
            }
        }

        /**
         * Works when publisher logged in: update number of counts on links and status.
         *
         * @param Array $views from wordpress
         * @return Array
         */
        public function fix_submission_counts( $views ) {
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
                    'post_type'   => 'submission',            
                    'post_status' => $type['status']            
                );

                $result = new \WP_Query( $query );

                if ( $type['status'] == NULL ):            
                    $class = ( $wp_query->query_vars['post_status'] == NULL ) ? ' class="current"' : '';
            
                    $views['all'] = sprintf(__('<a href="%s"'. $class .'>All <span class="count">(%d)</span></a>', 'all'),            
                                    admin_url('edit.php?post_type=submission'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'publish' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'publish' ) ? ' class="current"' : '';
            
                    $views['publish'] = sprintf(__('<a href="%s"'. $class .'>Published <span class="count">(%d)</span></a>', 'publish'),            
                                        admin_url('edit.php?post_status=publish&post_type=submission'),
                                        $result->found_posts);
            
                elseif ( $type['status'] == 'draft' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'draft' ) ? ' class="current"' : '';
            
                    $views['draft'] = sprintf(__('<a href="%s"'. $class .'>Draft'. ((sizeof($result->posts) > 1) ? "s" : "") .' <span class="count">(%d)</span></a>', 'draft'),            
                                    admin_url('edit.php?post_status=draft&post_type=submission'),            
                                    $result->found_posts);
            
                elseif ( $type['status'] == 'pending' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'pending' ) ? ' class="current"' : '';
            
                    $views['pending'] = sprintf(__('<a href="%s"'. $class .'>Pending <span class="count">(%d)</span></a>', 'pending'),            
                                        admin_url('edit.php?post_status=pending&post_type=submission'),            
                                        $result->found_posts);
            
                elseif( $type['status'] == 'trash' ):
            
                    $class = ( $wp_query->query_vars['post_status'] == 'trash' ) ? ' class="current"' : '';
            
                    $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),            
                                        admin_url('edit.php?post_status=trash&post_type=submission'),            
                                        $result->found_posts);
            
                endif;            
            }
            
            return $views;
        }

        public function add_back_button() {
            if ( 'submission' !== get_current_screen()->post_type ) {
                return;
            }

            $post_id = get_the_ID();
            if ( $post_id ) {
                $parent_id = wp_get_post_parent_id( $post_id );                
                if ( $parent_id ) {
                        $edit_url  = admin_url('post.php?post=' . $parent_id . '&action=edit');
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready( function($) {
                            $( '<a href="<?php echo $edit_url;?>" class="page-title-action">Back to issue</a>' ).insertBefore( ".wp-header-end" );
                        });     
                    </script>
                    <?php 
                }
            }
        }
    }
}

new DCSubmissions();
