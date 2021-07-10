<?php

namespace DiviChild\Classes\PostTypes;

// Creates class if not exists.
if ( ! class_exists('DCAttachments') ) {
    class DCAttachments {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            // only for publishers.
            add_action( 'pre_get_posts', array( $this, 'display_publisher_attachments_only') );
        }

        /**
         * Works when publisher logged in: only displays attachment owned by publisher.
         *
         * @param Object $wp_query from wordpress
         * @return void
         */
        public function display_publisher_attachments_only( $wp_query ) {
            global $pagenow;
            
            // only in admin area.
            if ( is_admin() ) {
                $user = wp_get_current_user();
                if ( in_array( 'publisher', (array) $user->roles ) ) {
                    //The user has the "publisher" role
                    if ( 'edit.php' == $pagenow ) {
                        $wp_query->set( 'author', $user->ID );
                    }
                    
                    add_filter('views_upload', array( $this, 'fix_media_counts' ) );
                }
            }
        }

        /**
         * Works when publisher logged in: update number of counts on media.
         *
         * @param Array $views from wordpress
         * @return Array
         */
        public function fix_media_counts( $views ) {
            global $wpdb, $current_user, $post_mime_types, $avail_post_mime_types;

            $views = array();
           
            $_num_posts = array();
           
            $count = $wpdb->get_results( "           
                SELECT post_mime_type, COUNT( * ) AS num_posts           
                FROM $wpdb->posts           
                WHERE post_type = 'attachment'           
                AND post_author = $current_user->ID           
                AND post_status != 'trash'           
                GROUP BY post_mime_type           
            ", ARRAY_A );
           
            foreach ( $count as $row ) {
                $_num_posts[$row['post_mime_type']] = $row['num_posts'];
            }
                      
            $_total_posts = array_sum($_num_posts);
           
            $detached = isset( $_REQUEST['detached'] ) || isset( $_REQUEST['find_detached'] );
           
            if ( !isset( $total_orphans ) ) {
                $total_orphans = $wpdb->get_var("           
                    SELECT COUNT( * )           
                    FROM $wpdb->posts           
                    WHERE post_type = 'attachment'           
                    AND post_author = $current_user->ID           
                    AND post_status != 'trash'           
                    AND post_parent < 1           
                ");
            }                
           
            $matches = wp_match_mime_types(array_keys($post_mime_types), array_keys($_num_posts));
           
            foreach ( $matches as $type => $reals ) {
                foreach ( $reals as $real ) {
                    $num_posts[$type] = ( isset( $num_posts[$type] ) ) ? $num_posts[$type] + $_num_posts[$real] : $_num_posts[$real];
                }    
            }           

            $class = ( empty($_GET['post_mime_type']) && !$detached && !isset($_GET['status']) ) ? ' class="current"' : '';
           
            $views['all'] = "<a href='upload.php'$class>" . sprintf( __('All <span class="count">(%s)</span>', 'uploaded files' ), number_format_i18n( $_total_posts )) . '</a>';
           
            foreach ( $post_mime_types as $mime_type => $label ) {           
                $class = '';
           
                if ( !wp_match_mime_types($mime_type, $avail_post_mime_types) )           
                   continue;

                if ( !empty($_GET['post_mime_type']) && wp_match_mime_types($mime_type, $_GET['post_mime_type']) )           
                   $class = ' class="current"';
           
                if ( !empty( $num_posts[$mime_type] ) )           
                   $views[$mime_type] = "<a href='upload.php?post_mime_type=$mime_type'$class>" . sprintf( translate_nooped_plural( $label[2], $num_posts[$mime_type] ), $num_posts[$mime_type] ) . '</a>';
           
            }
           
            $views['detached'] = '<a href="upload.php?detached=1"' . ( $detached ? ' class="current"' : '' ) . '>' . sprintf( __( 'Unattached <span class="count">(%s)</span>', 'detached files' ), $total_orphans ) . '</a>';
           
            return $views;
        }
    }
}

new DCAttachments();
