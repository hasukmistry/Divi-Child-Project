<?php

namespace DiviChild\Classes\MetaBoxes;

// Creates class if not exists.
if ( ! class_exists('DCSubmissionVendors') ) {
    class DCSubmissionVendors {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            if ( is_admin() ) {
                add_action( 'add_meta_boxes', array( $this, 'vendors' ) );
                add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

                add_action( 'admin_footer', array( $this, 'footer_scripts' ) );

                add_action( 'wp_ajax_get_vendors', array( $this, 'ajax_get_vendors' ) );
                add_action( 'wp_ajax_nopriv_get_vendors', array( $this, 'ajax_get_vendors' ) );            
            }
        }

        /**
         * This function will register metabox for submission post type.
         *
         * @return void
         */
        public function vendors() {
            add_meta_box( 'submission-vendors', __( 'Vendors for Submission', 'divi-child-metabox' ), array( $this, 'display_vendors'), 'submission', 'advanced' );
        }
              
        /**
         * Meta box display callback.
         *
         * @param WP_Post $submission Current submission object.
         */
        public function display_vendors( $submission ) {
            $vendor_latest_count = get_post_meta( $submission->ID, 'vendor_latest_count', true );
            $related_vendors = get_post_meta( $submission->ID, 'related_vendors', true );
            ?>
            <div class="tablenav bottom">
				<div class="alignleft actions bulkactions">
                    <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                    <select class="bulk-action-selector-bottom">
                        <option value="-1">Bulk Actions</option>
                        <option value="trash">Move to Trash</option>
                    </select>
                    <input type="button" class="button action dc-action" value="Apply">
		        </div>
		        <br class="clear">
	        </div>
            <table class="vendors-datatable wp-list-table widefat fixed striped posts" style="width:100%">
                <thead>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column no-searching-and-sorting selection"><input id="cb-select-all-1" type="checkbox"></th>
                        <th>Category</th>
                        <th>Vendor</th>
                    </tr>
                </thead>
                <tbody id="this-submission-vendors">
                    <?php
                        if( $related_vendors ) {
                            $relations = json_decode( $related_vendors );

                            $cat_ids = [];
                            $vendor_ids = [];

                            $cat_data = [];
                            $vendor_data = [];

                            // lets extract vendor ids and category slugs from relation.
                            foreach( $relations as $key => $relation ) {
                                $row = ! empty( $relation[1] ) ? $relation[1] : false;
                                if ( $row ) {
                                    if ( isset( $row->category ) && isset( $row->vendor ) ) {
                                        $cat_ids[] = $row->category;
                                        $vendor_ids[] = $row->vendor;
                                    }
                                }                                
                            }

                            // WP_Query arguments
                            $vendor_args = array(
                                'post_type'      => 'vendor',
                                'posts_per_page' => -1, 
                                'post__in'       => array_unique( $vendor_ids, SORT_NUMERIC ),
                            );

                            // The Query
                            $vendor_query = new \WP_Query( $vendor_args );
                            if ( $vendor_query->have_posts() ) :
                                while ( $vendor_query->have_posts() ) : $vendor_query->the_post();
                                    $vendor_data[ get_the_ID() ] = get_the_title();
                                endwhile;

                                wp_reset_postdata();
                            endif;

                            // WP_Term_Query arguments
                            $term_args = array(
                                'taxonomy' => [ 'vendor-category' ],
                                'include'  => array_unique( $cat_ids, SORT_NUMERIC ),
                            );

                            // The Term Query
                            $term_query = new \WP_Term_Query( $term_args );
                            if ( $term_query->terms ) :
                                foreach ( $term_query->terms as $term ) {
                                    $cat_data[ $term->term_id ] = $term->name;
                                }                                    
                            endif;

                            // lets create the rows in table
                            foreach( $relations as $key => $relation ) {
                                $row_key = ! empty( $relation[0] ) ? $relation[0] : false;
                                $row = ! empty( $relation[1] ) ? $relation[1] : false;
                                if ( $row ) {
                                    if ( isset( $row->category ) && isset( $row->vendor ) ) {
                                        ?>
                                            <tr role="row" class="hentry">
                                                <td><label class="screen-reader-text" for="cb-select-1">Select vendor</label><input type="checkbox" class="dc-row-checkbox" value="<?=$row_key;?>"></td>
                                                <td><strong><?= ! empty( $cat_data[$row->category] ) ? $cat_data[$row->category] : '';?></strong><div class="row-actions"><span class="trash"><a href="javascript:void(0);" class="submitdelete trash-relation-row" aria-label="Move this to the Trash" data-row="<?=$row_key;?>">Trash</a></span></div></td>
                                                <td><span><?= ! empty( $vendor_data[$row->vendor] ) ? $vendor_data[$row->vendor] : '';?></span></td>
                                            </tr>
                                        <?php
                                    }
                                }                                
                            }
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column no-searching-and-sorting selection"><input id="cb-select-all-2" type="checkbox"></th>
                        <th>Category</th>
                        <th>Vendor</th>
                    </tr>
                </tfoot>
            </table>
            <div style="display:none;" id="__form_data">
                <div id="__current_fields">
                    <?php
                        // Add nonce for security and authentication.
                        wp_nonce_field( 'add_vendors_to_this_submission', 'form_security_token' );
                    ?>
                    <input type="hidden" name="vendor_latest_count" id="vendor_latest_count" value="<?=$vendor_latest_count;?>" /> 
                    <input type="hidden" name="related_vendors" id="related_vendors" value='<?=$related_vendors;?>' /> 
                </div>
            </div>           
            <?php
            $terms = get_terms( array(
                'taxonomy' => 'vendor-category',
            ) );

            if ( $terms && ! is_wp_error( $terms ) ) {
                ?>
                <div id="_new_vendor_block" class="dc-block new-vendor-block hidden">
                    <div class="acf-fields -top">
                        <div class="acf-field" style="width: 49.5%; min-height: 90px;" data-width="49.5">
                            <div class="acf-label">
                                <label for="vendor-categories">Vendor Categories</label>
                            </div>
                            <div class="acf-input">
                                <div class="acf-input-wrap">
                                    <select name="vendor-categories" class="dc-select-dropdown vendor-category" data-placeholder="Select Category">
                                        <option></option>
                                        <?php
                                        foreach ( $terms as $term ) {
                                            ?>
                                            <option value="<?=$term->term_id;?>"><?=$term->name;?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="acf-field vendors-from-categories hidden" style="width: 49.5%; min-height: 90px;" data-width="49.5">
                            <div class="acf-label">
                                <label for="vendors">Vendors</label>
                            </div>
                            <div class="acf-input">
                                <div class="acf-input-wrap">
                                    <select name="vendors" class="dc-select-dropdown vendors" data-placeholder="Select Vendors">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div style="float:left;margin:0 0 10px 10px;"><input disabled name="save_vendor" type="button" class="button button-primary button-large dc-button save_vendor" id="save_vendor" value="Save"></div>
                        <div class="clear"></div>
                    </div> 
                </div>
                <div>
                    <div style="float:right;margin:20px 0px;"><input name="add_vendors" type="button" class="button button-primary button-large dc-button add_vendors" id="add_vendors" value="Add Vendors"></div>
                    <div class="clear"></div>
                </div>        
                <?php
            } else {
                echo "Server error OR There are no vendor categories now, Contact your administrator to report.";
            }         
        }

        public function footer_scripts() {
            $screen = get_current_screen();

            if ( 'submission' !== $screen->id ) {
                return;
            }
            ?>
            <script type="text/javascript">
                // script is located inside assets/js folder
            </script>
            <style type="text/css">
                #submission-vendors .acf-fields>.acf-field{
                    border-top:none;
                }
                #submission-vendors .new-vendor-block{
                    border: 1px solid #efefef;
                    margin-bottom:10px;
                }
                .dc-check-column input[type="checkbox"]{
                    margin-left:5px;
                }
                #this-submission-vendors .dc-column strong, #this-submission-vendors .dc-column span{
                    margin-left:8px;
                }
            </style>
            <?php
        }

        public function ajax_get_vendors() {
            $json = [];

            // Check for nonce security
            $nonce = $_GET['token'];

            if ( wp_verify_nonce( $nonce, 'vendor-search' ) ){
                $args = array(
                    's'           => $_GET['q'], // the search query
                    'tax_query'   => array(
                        array(
                            'taxonomy' => 'vendor-category',
                            'terms'    => $_GET['category']
                        )
                    ),
                    'post_type'   => 'vendor',
                    'post_status' => 'publish',
                    'order'       => 'ASC',
                    'orderby'     => 'title',
                    'numberposts' => -1
                );

                // if publisher logged in, then only show vendors created by logged in publisher
                $user = wp_get_current_user();
                if ( in_array( 'publisher', (array) $user->roles ) ) {
                    //The user has the "publisher" role
                    $args['author'] = $user->ID;
                }

                $vendors = get_posts( $args );

                if ( $vendors ) {
                    foreach ( $vendors as $vendor ) {
                        $json[] = [
                            'vendor_id'   => $vendor->ID,
                            'vendor_name' => $vendor->post_title,
                        ];
                    }
                    wp_reset_postdata();
                }
            }
            echo json_encode($json);

            exit();die();
        }

        /**
         * Handles saving the meta box.
         *
         * @param int     $post_id Post ID.
         * @param WP_Post $post    Post object.
         * @return null
         */
        public function save_metabox( $post_id, $post ) {
            // Add nonce for security and authentication.
            $nonce_name   = isset( $_POST['form_security_token'] ) ? $_POST['form_security_token'] : '';
            $nonce_action = 'add_vendors_to_this_submission';
    
            // Check if nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
                return;
            }
    
            // Check if user has permissions to save data.
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
    
            // Check if not an autosave.
            if ( wp_is_post_autosave( $post_id ) ) {
                return;
            }
    
            // Check if not a revision.
            if ( wp_is_post_revision( $post_id ) ) {
                return;
            }

            $vendor_latest_count = ! empty( $_POST['vendor_latest_count'] ) ? $_POST['vendor_latest_count'] : '';
            $related_vendors = ! empty( $_POST['related_vendors'] ) ? $_POST['related_vendors'] : '';

            update_post_meta( $post_id, 'vendor_latest_count', $vendor_latest_count );

            update_post_meta( $post_id, 'related_vendors', $related_vendors );
        }
    }
}

new DCSubmissionVendors();
