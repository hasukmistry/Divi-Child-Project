<?php // phpcs:ignore
/**
 * Google Drive Settings page.
 *
 * The file handles the rendering of the settings page.
 *
 * @since 1.1.0
 *
 * @package    Boldgrid_Backup
 * @subpackage Boldgrid_Backup/admin/partials/remote
 * @copyright  BoldGrid
 * @author     BoldGrid <support@boldgrid.com>
 *
 * @param string $folder_name
 * @param int    $retention_count
 * @param string $nickname
 *
 * phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
 */

defined( 'WPINC' ) || die;

?>

<form method="post">
	<?php wp_nonce_field( 'bgbkup-gd-settings', 'gd_auth' ); ?>

	<h1><?php echo BOLDGRID_BACKUP_TITLE . ' - ' . __( 'Google Drive Settings', 'boldgrid-backup' ); ?></h1>

	<table class="form-table">
		<tr>
			<th><?php echo __( 'Folder name (A folder in Google Drive to store your backups, will be created if it doesn\'t exist)', 'boldgrid-backup' ); ?></th>
			<td><input type="text" name="folder_name" value="<?php echo esc_attr( $folder_name ); ?>" min="1" required /></td>
		</tr>
		<tr>
			<th><?php echo __( 'Retention (Number of backup archives to retain)', 'boldgrid-backup' ); ?></th>
			<td><input type="number" name="retention_count" value="<?php echo esc_attr( $retention_count ); ?>" min="1" required /></td>
		</tr>
		<tr>
			<th><?php echo __( 'Nickname (If you would like to refer to this account as something other than Google Drive)', 'boldgrid-backup' ); ?></th>
			<td><input type="text" name="nickname" value="<?php echo esc_attr( $nickname ); ?>" maxlength="63" /></td>
		</tr>
	</table>

	<input class="button button-primary" type="submit" name="submit" value="<?php echo __( 'Save changes', 'boldgrid-backup' ); ?>" />
</form>
