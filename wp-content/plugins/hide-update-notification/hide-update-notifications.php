<?php
/**
 * Plugin Name: Hide Update Notifications
 * Plugin URI: https://cssigniter.com
 * Description: Hide update notifications from Publishers
 * Version: 1.0
 * Author: Premier Weddings
 * Author URI: https://cssigniter.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
function hide_core_update_notifications_from_users() {
if ( ! current_user_can( 'update_core' ) ) {
remove_action( 'admin_notices', 'update_nag', 3 );
}
}
add_action( 'admin_head', 'hide_core_update_notifications_from_users', 1 );