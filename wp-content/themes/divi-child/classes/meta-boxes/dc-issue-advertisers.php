<?php

namespace DiviChild\Classes\MetaBoxes;

// Creates class if not exists.
if ( ! class_exists('DCIssueAdvertisers') ) {
    class DCIssueAdvertisers {
        /**
         * constructor method.
         *
         * @return void
         */
        public function __construct() {
            add_action( 'add_meta_boxes', array( $this, 'advertisers' ) );
        }

        /**
         * This function will register metabox for issue post type.
         *
         * @return void
         */
        public function advertisers() {
            add_meta_box( 'issue-advertisers', __( 'Advertisers', 'divi-child-metabox' ), array( $this, 'display_advertisers' ), 'issue' );
        }
        
        /**
         * Meta box display callback.
         *
         * @param WP_Post $issue Current issue object.
         */
        public function display_advertisers( $issue ) {
            $query = new \WP_Query( array( 
                'meta_query' => array(  
                    array(
                        'key'     => 'issue_advertising_in',
                        'value'   => $issue->ID,
                        'compare' => '=',
                    ),
                ),
                'post_type'      => 'advertiser',
                'posts_per_page' => -1,
                'author'         => $issue->post_author,
            ) );

            if ( $query->have_posts() ) :
            ?>   
            <table class="dc-datatable wp-list-table widefat fixed striped posts" style="width:100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Size</th>
                        <th>Placement</th>
                        <th>Created date</th>
                    </tr>
                </thead>
                <tbody id="post-advertisers">
                    <?php
                        while ( $query->have_posts() ) : $query->the_post();
                            $advertiser_fields = get_fields( get_the_ID() );

                            $ad_size   = ! empty( $advertiser_fields['ad_size'] ) ? $advertiser_fields['ad_size'] : '';
                            $placement = ! empty( $advertiser_fields['ad_placement'] ) ? $advertiser_fields['ad_placement'] : '—';

                            $ad_size_value = '';
                            if ( '1by2_page' === $ad_size ) {
                                $ad_size_value = '1/2 Page';
                            } elseif ( '1by4_page' === $ad_size ) {
                                $ad_size_value = '1/4 Page';
                            } elseif ( '2_page_spread' === $ad_size ) {
                                $ad_size_value = '2 Page Spread';
                            } elseif ( 'full_page' === $ad_size ) {
                                $ad_size_value = 'Full Page';
                            } elseif ( '1by8_page' === $ad_size ) {
                                $ad_size_value = '1/8 Page';
                            } elseif ( 'coupon_ad' === $ad_size ) {
                                $ad_size_value = 'Coupon'; 
                            } elseif ( 'front_cover' === $ad_size ) {
                                $ad_size_value = 'Front Cover';                                
                            } elseif ( 'back_coupon' === $ad_size ) {
                                $ad_size_value = 'Back Cover Coupon';                                
                            }
                            $ad_size_value = ! empty( $ad_size_value ) ? $ad_size_value : '—';

                            $creation_date = get_the_date( 'Y/m/d' );
                    ?>
                    <tr class=" hentry">
                        <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">      
                            <?php
                                $edit_url  = admin_url('post.php?post=' . get_the_ID() . '&action=edit');
                            ?>                      
                            <strong><a class="row-title" href="<?php echo $edit_url;?>" aria-label="(Edit)"><?php echo get_the_title();?></a></strong>
                            <div class="row-actions">
                                <span class="edit"><a href="<?php echo $edit_url;?>" aria-label="Edit">Edit</a></span>
                            </div>
                        </td>
                        <td><?=$ad_size_value;?></td>
                        <td><?=$placement;?></td>
                        <td><?=$creation_date;?></td>
                    </tr>
                    <?php
                        endwhile;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Title</th>
                        <th>Size</th>
                        <th>Placement</th>
                        <th>Created date</th>
                    </tr>
                </tfoot>
            </table>
            <?php                
                wp_reset_postdata();
            else :
                echo "There are currently no advertisers assigned to this issue, Create Vendors and then Advertisers.";
            endif;
        }        
    }
}

new DCIssueAdvertisers();
