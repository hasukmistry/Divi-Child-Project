<?php while ( have_posts() ) : the_post(); ?>
<?php

acf_form_head();

get_header('issue');

$current_publisher_id      = get_the_author_meta('ID');
$current_publication_title = get_field('publication_name', 'user_'. $current_publisher_id );

$current_publisher_username = ! empty( get_the_author_meta('display_name') ) ? get_the_author_meta('display_name') : get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name');
$current_publisher_title    = ! empty($current_publication_title) ? $current_publication_title : $current_publisher_username;
?>

<div class="container clearfix submission_req_form">
    <h1>Submission Form</h1>
    <h4><?php echo $current_publisher_title . ' - ' . get_the_title();?></h4>
    
    <?php 
        global $post;
        $post_slug = $post->post_name;

        $terms_and_conditions_page = \DiviChild\Classes\Inc\DCPageLinks::terms_and_conditions();

        $terms_and_conditions_url = esc_url( add_query_arg( array( 
            'issue' => $post_slug,
        ), esc_url( $terms_and_conditions_page ) ) );

        // https://www.advancedcustomfields.com/resources/acf_form/
        // https://medium.com/techcompose/how-to-add-advanced-custom-fields-acf-to-a-frontend-form-2b89c8cfdcee
        $form_settings = [
            'id'                => 'issue-submission-form',
            'field_groups'      => ['group_5e49302647d5b'],
            'post_id'           => 'new_post',
            'post_title'        => true,
            'new_post'        => array(
                'post_type'   => 'submission',
                'post_status' => 'publish',
                'post_parent' => get_the_ID(),
                'post_author' => $current_publisher_id,
            ),
            'submit_value'      => __( 'Submit', 'acf' ),
            'updated_message'   => __( 'Submission successfully submitted.', 'acf'),
            'html_after_fields' => '
            <div class="acf-field acf-checkbox-fields acf-field-text acf-field_copyright_holder is-required" data-name="copyright_holder" data-type="text" data-key="field_copyright_holder" data-required="1">
                <div class="acf-input">
                    <label><input type="checkbox" id="copyright_holder" name="copyright_holder" value="yes" required="required"> I certify that I am the copyright holder for the images included in this submission.</label>
                </div>
            </div>
            <div class="acf-field acf-checkbox-fields acf-field-text acf-field_accept_terms_and_conditions is-required" data-name="accept_terms_and_conditions" data-type="text" data-key="field_accept_terms_and_conditions" data-required="1">
                <div class="acf-input">
                    <label><input type="checkbox" id="accept_terms_and_conditions" name="accept_terms_and_conditions" value="yes" required="required"> I accept the ' . get_the_title() . ' <a target="_blank" href="' . $terms_and_conditions_url . '">Terms and Conditions</a> by clicking Submit button.</label>
                </div>
            </div>',
        ];

        acf_form( $form_settings ); 
    ?>
</div>

<?php endwhile; ?>

<?php
get_footer('issue');