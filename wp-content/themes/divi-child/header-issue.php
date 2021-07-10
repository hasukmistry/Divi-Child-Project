<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php
	elegant_description();
	elegant_keywords();
	elegant_canonical();

	/**
	 * Fires in the head, before {@see wp_head()} is called. This action can be used to
	 * insert elements into the beginning of the head before any styles or scripts.
	 *
	 * @since 1.0
	 */
	do_action( 'et_head_meta' );

	$template_directory_uri = get_template_directory_uri();
?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>

	<?php wp_head(); ?>
</head>
<?php
    $current_publisher_id = get_the_author_meta('ID');
    
    $form_logo = false;
    $image_url = $template_directory_uri . '/images/logo.png';

    if ( function_exists( 'get_field' ) ) {
        $form_logo = get_field('publisher_logo', 'user_' . $current_publisher_id );

        $image_url = $form_logo['sizes']['large'];
    }
    
?>
<body <?php body_class(); ?>>
<div id="page-container"<?php echo et_core_intentionally_unescaped( $page_container_style, 'fixed_string' ); ?> class="et-animated-content">
    <header id="main-header" data-height-onload="100" class="et-fixed-header">
        <div class="container clearfix et_menu_container">
            <div class="issue_logo_container">
                <img src="<?php echo esc_attr( $image_url ); ?>" alt="" id="logo" />
            </div>
        </div>
    </header>