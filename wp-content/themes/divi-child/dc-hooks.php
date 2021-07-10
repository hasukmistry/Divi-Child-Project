<?php

function enqueue_datatables( $hook ) {
    $screen = get_current_screen();
    $user = wp_get_current_user();

    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_style( 'datatables-style', get_stylesheet_directory_uri() . '/plugins/datatables/datatables.css' );
    
        wp_enqueue_script( 'datatables-script', get_stylesheet_directory_uri() . '/plugins/datatables/datatables.min.js', array('jquery'), '1.10.20' );
    
        $wp_datatables     = '/plugins/datatables/wp-datatables.js';
        $wp_datatables_css = '/plugins/datatables/wp-datatables.css';
    
        $acf_hooks = '/acf-fields/acf-hooks.js';
        $events    = '/events.js';

        $theme = '/theme.css';

        $wp_datatables_ver     = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $wp_datatables ));
        $wp_datatables_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $wp_datatables_css ));
    
        $acf_hooks_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $acf_hooks ));
        $events_ver    = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $events ));

        $theme_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme ));

        wp_enqueue_style( 'wp-datatables-style', get_stylesheet_directory_uri() . $wp_datatables_css, false, $wp_datatables_css_ver );
    
        wp_register_script( 'wp-datatables', get_stylesheet_directory_uri() . $wp_datatables, array('datatables-script'), $wp_datatables_ver );

        $pending_checked = 'no';
        if ( ! in_array( 'publisher', $user->roles ) ) {
            $pending_checked = 'yes';
        }

        wp_localize_script( 'wp-datatables', 'input', [
            'submission_statuses' => array_values(get_submission_status_list()),
            'pending_checked' => $pending_checked,
        ] );

        wp_enqueue_script( 'wp-datatables' );

        // Register the script
        wp_register_script( 'acf-hooks', get_stylesheet_directory_uri() . $acf_hooks, array( 'jquery', 'acf-input', 'acf-field-group' ), $acf_hooks_ver, true );

        wp_register_script( 'events', get_stylesheet_directory_uri() . $events, array( 'jquery' ), $events_ver, true );
        
        // only show vendors created by logged in publisher
        $publisher = '';
        if ( in_array( 'publisher', (array) $user->roles ) ) {
            //The user has the "publisher" role
            $publisher = $user->ID;
        }
        
        wp_localize_script( 'acf-hooks', 'global_params', [
            'publisher' => $publisher,
        ] );

        wp_enqueue_script( 'acf-hooks' );

        wp_enqueue_script( 'events' );

        wp_enqueue_style( 'dc-theme-css', get_stylesheet_directory_uri() . $theme, false, $theme_ver );
    }

    // submission page script
    if ( 'submission' === $screen->id ) {
        $script_file = '/classes/meta-boxes/assets/js/dc-submission-vendors.js';

        $script_file_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $script_file ));

        wp_register_script( 'dc-submission-vendors', get_stylesheet_directory_uri() . $script_file, array('jquery'), $script_file_ver );

        wp_localize_script( 'dc-submission-vendors', 'script_params', [
            'ajax_url' => admin_url( "admin-ajax.php" ),
            'nonce'    => wp_create_nonce("vendor-search"),
        ] );

        wp_enqueue_script( 'dc-submission-vendors' );
    }
    return;
}

add_action( 'admin_enqueue_scripts', 'enqueue_datatables' );

function post_title_submissions_form( $field ) {
    if( is_singular( 'issue' ) ) { // if on the issue page
        $field['label']       = 'Photographer/Business Name (as it should appear on your vendor credit)';
        $field['placeholder'] = 'Jane Doe Photography';
    }
    return $field;
}
add_filter('acf/load_field/name=_post_title', 'post_title_submissions_form');

function results_for_publishers_only( $args, $field, $post_id ) {
    // only show vendors created by logged in publisher
    $user = wp_get_current_user();
    if ( in_array( 'publisher', (array) $user->roles ) ) {
        //The user has the "publisher" role
        $args['author'] = $user->ID;
    }

	// return
    return $args;
}

// filter to customize info field for assignment section in create ticket
function assigned_to_info_field( $field ) {
    global $post;

    $info = DiviChild\Classes\Inc\DCAssignmentInfo::get_assigned_to( $post );

    $field['message'] = $info['content'];
    $field['value'] = $info['user_email'];

    return $field;
}

// filter to customize info field for assignment section in issue
function assigned_to_issue_info_field( $field  ) {
    global $post;

    $info = DiviChild\Classes\Inc\DCAssignmentInfo::get_assigned_to_for_issue( $post );

    $field['message'] = $info['content'];
    $field['value'] = $info['user_email'];

    return $field;
}

function load_original_issue( $field ) {
    global $post;

    if ( is_null( $field['value'] ) ) {
        $field['value'] = $post->post_parent;
    }

    return $field;
}

function reassign_parent_issue( $post_id ) {
    if ( 'submission' !== get_post_type( $post_id ) ) {
        return;
    }

    $original_issue = get_field('original_issue', $post_id);

    // publisher id AKA author id of issue
    $author_id = get_post_field( 'post_author', $original_issue );

    $parent_issue = wp_get_post_parent_id( $post_id );

    if ( $original_issue && $parent_issue !== $original_issue ) {
        // lets remove save post action to avoid infinite loop
        remove_action('acf/save_post', 'reassign_parent_issue');

        wp_update_post(
            array(
                'ID' => $post_id, 
                'post_parent' => $original_issue,
                'post_author' => $author_id
            )
        );

        add_action('acf/save_post', 'reassign_parent_issue');
    }
}

add_action( 'init', function() {
    $terms = get_terms( array(
        'taxonomy' => 'vendor-category',
    ) );

    if ( $terms && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            add_filter('acf/fields/post_object/query/key=field_5e53fb4bfef92' . $term->term_id , 'results_for_publishers_only', 10, 3);
        }
    }

    // advertiser form - advertiser/vendor field
    add_filter('acf/fields/post_object/query/key=field_5e6e40faf0574', 'results_for_publishers_only', 10, 3);

    // advertiser form - issue advertising in field
    add_filter('acf/fields/post_object/query/key=field_5e6e4151e8b99', 'results_for_publishers_only', 10, 3);

    // assigning a value - assignment section for tickets
    add_filter('acf/load_field/key=field_5ee46badc97b4', 'assigned_to_info_field');

    // assigning a value - assignment section for issues
    add_filter('acf/load_field/key=field_608582bf6ca2e', 'assigned_to_issue_info_field');

    // load parent issue for submission (when acf field value is empty)
    add_filter('acf/load_field/key=field_608d68486a991', 'load_original_issue');
    add_filter('acf/fields/post_object/query/key=field_608d68486a991', 'results_for_publishers_only', 10, 3);
    add_action('acf/save_post', 'reassign_parent_issue');
});


// sort uploaded files based on version
function sort_file_information( $value, $post_id, $field ) {
	
	// vars
	$order = array();
	
	
	// bail early if no value
	if( empty($value) ) {
		
		return $value;
		
	}
	
	
	// populate order
	foreach( $value as $i => $row ) {
		
		$order[ $i ] = $row['field_5ee4c80cf9fe8'];
		
	}
	
	
	// multisort
	array_multisort( $order, SORT_DESC, $value );
	
	
	// return	
	return $value;
	
}

add_filter('acf/load_value/name=file_information', 'sort_file_information', 10, 3);


add_filter('get_sample_permalink_html', 'perm', '',4);

function perm($return, $id, $new_title, $new_slug){
    // https://wordpress.stackexchange.com/questions/49803/remove-the-edit-button-in-posts-for-permalinks-on-certain-user-roles-wp-3-3
    $post = get_post( $id );

    if( $post && $post->post_type === 'issue' )
    {
        // regex replace
        return preg_replace(
            '/<span id="edit-slug-buttons">.*<\/span>|<span id=\'view-post-btn\'>.*<\/span>/i', 
            '<span id="copy-slug-buttons"><button type="button" class="copy-slug button button-small hide-if-no-js" data-permalink="' . get_post_permalink($id) . '" aria-label="' . __( 'Copy permalink' ) . '">' . __( 'Copy' ) . '</button><span id="copy_status">Copied</span></span>', 
            $return
        );
    }

    return $return;
}

add_filter( 'wp_dropdown_users_args', 'add_publishers_to_author_dropdown', 10, 2 );
// Allows admin to select publisher as an author
function add_publishers_to_author_dropdown($query_args, $r) {
    $user = wp_get_current_user();
    $allowed_roles = array('administrator');
    if ( array_intersect( $allowed_roles, $user->roles ) ) {
        // Use this array to specify multiple roles to show in dropdown
        $query_args['role__in'] = array( 'publisher', 'administrator' );

        // Unset the 'who' as this defaults to the 'author' role
        unset( $query_args['who'] );
    }

    return $query_args;
}

// add setting menu in backend
add_action('admin_menu', function() {
    $user = wp_get_current_user();
    $allowed_roles = array('administrator');
    if ( array_intersect( $allowed_roles, $user->roles ) ) {
        add_menu_page(
            'Website Settings',
            'Website',
            'manage_options',
            'website-settings',
            function() {
                echo '<p>Settings Dashboard is coming soon...</p>';
            },
            'dashicons-admin-generic'
        );
    }
});

function add_validation_vars( $vars ){
    $vars[] = "issue";
    return $vars;
}
add_filter( 'query_vars', 'add_validation_vars' );

function tpl_404() {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); 
    exit();
}

function post_exists_by_slug( $post_slug ) {
    $loop_posts = new WP_Query( array( 'post_type' => 'issue', 'name' => $post_slug, 'posts_per_page' => 1, 'fields' => 'ids' ) );
    return ( $loop_posts->have_posts() ? $loop_posts->posts[0] : false );
}

function get_post_id_by_slug($post_slug) {
    $found = post_exists_by_slug( $post_slug );
    if ( $found ) {
        return $found;
    }
}

function get_privacy_policy( $publisher_id ) {
    $publisher_terms_and_conditions = get_field('terms_and_conditions', 'user_'. $publisher_id );

    if ( ! empty( $publisher_terms_and_conditions ) ) {
        return $publisher_terms_and_conditions;
    }

    $common_terms_and_conditions = get_field('terms_and_conditions', 'option');

    return $common_terms_and_conditions;
}

// setup valid redirects
function site_template_redirect() {
    if ( basename( get_page_template() ) === 'publisher-privacy-policy.php' 
        && empty( get_query_var('issue') ) ) {
            tpl_404();
    }

    if ( basename( get_page_template() ) === 'publisher-privacy-policy.php' 
        && ! post_exists_by_slug( get_query_var('issue') ) ) {
            tpl_404();
    }
}
add_action( 'template_redirect', 'site_template_redirect' );

function custom_screen_access() {
    // only in wordpress admin area
    if ( is_admin() ) {
        $user = wp_get_current_user();

        if ( in_array( 'publisher', (array) $user->roles ) ) {
            $currentScreen = get_current_screen();
            if ( 'edit-ticket' === $currentScreen->id && 'trash' === $_REQUEST['post_status']) {
                wp_die('Sorry, you are not allowed to access this page.');
            }
        }
    }
}
add_action( 'current_screen', 'custom_screen_access' );

/**
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function dc_login_redirect( $redirect_to, $request, $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( in_array( 'publisher', $user->roles ) || in_array( 'sub_admin', $user->roles ) ) {
            // redirect them to the default place
            return admin_url('index.php');
        } 

        return $redirect_to;
    } else {
        return $redirect_to;
    }
}
 
add_filter( 'login_redirect', 'dc_login_redirect', 10, 3 );