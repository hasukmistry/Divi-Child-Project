<?php

namespace DiviChild\Classes\MetaBoxes;

// Creates class if not exists.
if ( ! class_exists('DCIssueSubmissions') ) {
    class DCIssueSubmissions {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'add_meta_boxes', array( $this, 'submissions' ) );

            add_action( 'trashed_post', array( $this, 'after_trashed_submission' ) );

            add_action( 'admin_footer', array( $this, 'bulk_actions' ) ); 

            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            add_action( 'wp_ajax_bulk_actions', array( $this, 'ajax_bulk_actions' ) );
            add_action( 'wp_ajax_nopriv_bulk_actions', array( $this, 'ajax_bulk_actions' ) );
        }

        /**
         * This function will register metabox for issue post type.
         *
         * @return void
         */
        public function submissions() {
            add_meta_box( 'issue-submissions', __( 'Submissions', 'divi-child-metabox' ), array( $this, 'display_submissions'), 'issue' );
        }
              
        /**
         * Meta box display callback.
         *
         * @param WP_Post $issue Current issue object.
         */
        public function display_submissions( $issue ) {
            $user = wp_get_current_user();

            $pending_checked = '';
            if ( ! in_array( 'publisher', $user->roles ) ) {
                $pending_checked = 'checked';
            }

            $query = new \WP_Query( array( 
                'post_parent'    => $issue->ID,
                'post_type'      => 'submission',
                'posts_per_page' => -1,
                'author'         => $issue->post_author,
            ) );

            if ( $query->have_posts() ) :
            ?>
            <div class="tablenav bottom">
				<div class="alignleft actions bulkactions">
                    <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                    <select id="action1" name="action1" id="bulk-action-selector-bottom">
                        <option value="-1">Bulk Actions</option>
                        <option value="trash">Move to Trash</option>
                    </select>
                    <input type="button" id="doaction1" class="button action" value="Apply">

                    <div class="dc-custom-filters">
                        <label>Hide Submissions: </label>
                        <input type="checkbox" checked class="dc-custom-cbFilter hide_rejected" /> Rejected
                        <input type="checkbox" <?php echo $pending_checked;?> class="dc-custom-cbFilter hide_pending" /> Pending
                    </div>
		        </div>
		        <br class="clear">
	        </div>             
            <table class="dc-datatable wp-list-table widefat fixed striped posts" style="width:100%">
                <thead>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column no-searching-and-sorting selection"><input id="cb-select-all-1" type="checkbox"></th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Submitter</th>
                        <th>Placement</th>
                        <th>Created date</th>
                    </tr>
                </thead>
                <tbody id="post-submissions">
                    <?php
                        while ( $query->have_posts() ) : $query->the_post();
                            $submission_fields = get_fields( get_the_ID() );

                            $status    = ! empty( $submission_fields['submission_status'] ) ? $submission_fields['submission_status'] : 'pending';
                            $type      = ! empty( $submission_fields['submission_type'] ) ? $submission_fields['submission_type'] : '';
                            $placement = ! empty( $submission_fields['placement'] ) ? $submission_fields['placement'] : '';

                            $submission_status = get_submission_status( $status );
                            $submission_type   = get_submission_type( $type );

                            $creation_date = get_the_date( 'Y/m/d' );
                    ?>
                    <tr class="post-<?php echo get_the_ID();?> type-submission hentry" style="display: table-row;">
			            <th scope="row" class="check-column reset-margin-left">			
                            <label class="screen-reader-text" for="cb-select-1">
                                Select submission
                            </label>
			                <input type="checkbox" name="submissions" value="<?=get_the_ID();?>">
                        </th>
                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">      
                            <?php
                                $edit_url  = admin_url('post.php?post=' . get_the_ID() . '&action=edit');
                                $trash_url = wp_nonce_url( admin_url('post.php?post=' . get_the_ID() . '&action=trash'), 'trash-post_'.get_the_ID() );

                                // make custom title as per requested changes
                                $display_title = '';
                                $custom_title  = '';

                                if ( 'styled_shoot' === $type) {
                                    $styled_shoot_title = get_field( "styled_shoot_title", get_the_ID() );

                                    $custom_title .= $styled_shoot_title;

                                    if ( empty($custom_title) ) {
                                        $custom_title .= $submission_type;
                                    }
                                } else {
                                    $spouse_1_first_name = get_field( "spouse_1_first_name", get_the_ID() );
                                    $spouse_2_first_name = get_field( "spouse_2_first_name", get_the_ID() );
    
                                    $custom_title .= $spouse_1_first_name;
    
                                    if ( !empty($custom_title) ) {
                                        $custom_title .= '/' . $spouse_2_first_name;
                                    }

                                    if ( !empty($custom_title) ) {
                                        $custom_title .= ' ' . $submission_type;
                                    }
                                }

                                if ( !empty($custom_title) ) {
                                    $display_title = $custom_title;
                                }
                            ?>                      
                            <strong><a class="row-title" href="<?php echo $edit_url;?>" aria-label="(Edit)"><?php echo $display_title;?></a></strong>
                            <div class="row-actions">
                                <span class="edit">
                                    <a href="<?php echo $edit_url;?>" aria-label="Edit">Edit</a>
                                    <?php if( current_user_can('delete_post', get_the_ID()) ) : ?>
                                     | 
                                    <?php endif; ?>
                                </span>

                                <?php if( current_user_can('delete_post', get_the_ID()) ) : ?>
                                    <span class="trash"><a onclick="return confirm('Are you sure?')" href="<?php echo $trash_url;?>" class="submitdelete" aria-label="Move '<?php echo $display_title;?>' to the Trash">Trash</a></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?=$submission_type;?></td>
                        <td>
                            <?=$submission_status;?>
                            <span class="edit-submission-status">
                                <a class="edit-submission-status-edit" href="#">( Edit )</a>
                            </span>
                        </td>
                        <td><?=get_the_title();?></td>
                        <td><?=$placement;?></td>
                        <td><?=$creation_date;?></td>
                    </tr>
                    <?php
                        endwhile;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column no-searching-and-sorting selection"><input id="cb-select-all-2" type="checkbox"></th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Submitter</th>
                        <th>Placement</th>
                        <th>Created date</th>
                    </tr>
                </tfoot>
            </table>
            <?php                
                wp_reset_postdata();
            else :
                echo "There are no submissions now, Create Issue or Distribute Issue to publishers to accept submissions";
            endif;
        }

        public function after_trashed_submission( $post_id ) {
            $screen = get_current_screen();
            if ( 'submission' == $screen->id && 'submission' === $screen->post_type  ) {
                $ids         = $post_id;
                $post_parent = wp_get_post_parent_id( $post_id );
                $edit_url    = admin_url('post.php?post=' . $post_parent . '&action=edit&trashed=1');
                
                wp_redirect( $edit_url );
                exit;
            }
        }

        public function bulk_actions() {
            $nonce = wp_create_nonce( 'issue_submissions_bulk_actions');
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function( $ ) {
                    $( "#doaction1" ).click(function() {
                        let select_val = $( "#action1" ).val();
                        let referer = $("input:hidden[name=_wp_http_referer]").val();
                        
                        let selected_ids = [];
                
                        if ( '-1' !== select_val ) {            
                            $("input:checkbox[name=submissions]:checked").each(function(){
                                selected_ids.push($(this).val());
                            });

                            var data = {
                                'action': 'bulk_actions',
                                '_wp_nonce': '<?=$nonce;?>',
                                'perform': select_val,
                                'posts': selected_ids
                            };

                            jQuery.post(ajaxurl, data, function(response) {
                                window.location = referer;
                            });                       
                        }
                    });
                });
            </script>
            <?php
        }

        public function ajax_bulk_actions() {
            if ( wp_verify_nonce( $_REQUEST['_wp_nonce'], 'issue_submissions_bulk_actions' ) ) {
                $posts   = ! empty( $_REQUEST['posts'] ) ? $_REQUEST['posts'] : []; 
                $perform = ! empty( $_REQUEST['perform'] ) ? $_REQUEST['perform'] : '';
                
                switch($perform) {
                    case 'trash':
                        if ( is_array( $posts ) ) {
                            foreach( $posts as $post ) {
                                wp_trash_post( $post );
                            }
                        } else {
                            wp_trash_post( $posts );
                        }                        
                        break;
                    default:
                        break;
                }
            }
            echo '1';
            wp_die();
        }

        public function enqueue_scripts( $hook ) {
            $screen = get_current_screen();

            if ( is_admin() && $screen && property_exists( $screen, 'id' )  ) {
                if ( 'issue' === $screen->id ) {
                    $theme_css = '/assets/css/dc-admin-edit-single-issue.css';
                    $theme_css_ver = date( 'ymd-Gis', filemtime( get_stylesheet_directory() . $theme_css ));
                
                    wp_enqueue_style( 'quick-edit-single-issue-theme', get_stylesheet_directory_uri() . $theme_css, false, $theme_css_ver );
                }
            }
        }
    }
}

new DCIssueSubmissions();
