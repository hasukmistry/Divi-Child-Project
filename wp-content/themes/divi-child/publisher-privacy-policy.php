<?php
/**
* Template Name: Publisher Privacy Policy
*
*/
use DiviChild\Classes\Inc\DCPageLinks;

$issue = get_query_var('issue');
$issue_post = get_post_id_by_slug($issue);
$current_publisher_id = get_post_field( 'post_author', $issue_post );
$current_publication_title = get_field('publication_name', 'user_'. $current_publisher_id );

$current_publisher_username = ! empty( get_the_author_meta('display_name', $current_publisher_id) ) ? get_the_author_meta('display_name', $current_publisher_id) : get_the_author_meta('first_name', $current_publisher_id) . ' ' . get_the_author_meta('last_name', $current_publisher_id);
$current_publisher_title    = ! empty($current_publication_title) ? $current_publication_title : $current_publisher_username;

$page = DCPageLinks::terms_and_conditions_obj();

get_header('publisher-privacy-policy');
?>

<div class="container clearfix submission_req_form">
    <h1><?php echo $page['title']; ?></h1>
    <h4><?php echo $current_publisher_title . ' - ' . get_the_title($issue_post);?></h4>

    <div class="privacy_policy">
        <?php 
            $content = get_privacy_policy( $current_publisher_id );

            if ( ! empty( $content ) ) {
                echo sprintf("%s", $content);
            }
        ?>
    </div>
</div>

<?php
get_footer('publisher-privacy-policy');
