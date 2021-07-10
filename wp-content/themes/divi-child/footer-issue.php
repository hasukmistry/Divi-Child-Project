    <footer id="main-footer">
        <?php /* ?>
        <?php get_sidebar( 'footer' ); ?>

        <?php if ( has_nav_menu( 'footer-menu' ) ) : ?>

            <div id="et-footer-nav">
                <div class="container">
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-menu',
                            'depth'          => '1',
                            'menu_class'     => 'bottom-nav',
                            'container'      => '',
                            'fallback_cb'    => '',
                        ) );
                    ?>
                </div>
            </div> <!-- #et-footer-nav -->

        <?php endif; ?>

        <div id="footer-bottom">
            <div class="container clearfix">
            <?php
                if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
                    get_template_part( 'includes/social_icons', 'footer' );
                }

                // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                echo et_core_fix_unclosed_html_tags( et_core_esc_previously( et_get_footer_credits() ) );
                // phpcs:enable
            ?>
            </div>	<!-- .container -->
        </div>
        <?php */ ?>
    </footer>
    </div>
    <?php wp_footer(); ?>
</body>
</html>