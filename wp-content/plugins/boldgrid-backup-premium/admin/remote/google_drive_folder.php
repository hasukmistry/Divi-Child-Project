<?php // phpcs:ignore
/**
 * Google Drive Folder class.
 *
 * @link  https://www.boldgrid.com
 * @since 1.1.0
 *
 * @package    Boldgrid_Backup
 * @subpackage Boldgrid_Backup/admin/remote
 * @copyright  BoldGrid.com
 * @author     BoldGrid <support@boldgrid.com>
 */

/**
 * Class: Boldgrid_Backup_Premium_Admin_Remote_Google_Drive_Folder
 *
 * @since 1.1.0
 */
class Boldgrid_Backup_Premium_Admin_Remote_Google_Drive_Folder {
	/**
	 * Our last error message.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	public $last_error;

	/**
	 * The core class object.
	 *
	 * @since 1.1.0
	 * @access private
	 * @var Boldgrid_Backup_Admin_Core
	 */
	private $core;

	/**
	 * Our parent folder name.
	 *
	 * All backups will be stored off the root in a folder named after the parent plugin, such as:
	 * /BOLDGRID_BACKUP_TITLE/DomainA.com/backup1.zip
	 * /BOLDGRID_BACKUP_TITLE/DomainB.com/backup1.zip
	 *
	 * @since 1.1.0
	 * @access private
	 * @var string
	 */
	private $parent_folder_name = 'BoldGrid Backup';

	/**
	 * An instance of Boldgrid_Backup_Premium_Admin_Core.
	 *
	 * @since 1.1.0
	 * @access private
	 * @var Boldgrid_Backup_Premium_Admin_Core
	 */
	private $premium_core;

	/**
	 * Constructor.
	 *
	 * @since 1.1.0
	 *
	 * @param Boldgrid_Backup_Admin_Core         $core         Boldgrid_Backup_Admin_Core object.
	 * @param Boldgrid_Backup_Premium_Admin_Core $premium_core Boldgrid_Backup_Premium_Admin_Core object.
	 */
	public function __construct( Boldgrid_Backup_Admin_Core $core, Boldgrid_Backup_Premium_Admin_Core $premium_core ) {
		$this->core         = $core;
		$this->premium_core = $premium_core;
		$this->last_error   = __( 'Unknown error.', 'boldgrid-backup' );
	}

	/**
	 * Create a folder.
	 *
	 * @since 1.1.0
	 *
	 * @link https://github.com/google/google-api-php-client/issues/860
	 *
	 * @param  string $name      The name of the folder to create.
	 * @param  string $parent_id Optional, a parent folder id.
	 * @return mixed             Google_Service_Drive_DriveFile on Success, false on failure.
	 */
	public function create( $name, $parent_id = '' ) {
		$service = $this->premium_core->google_drive->client->get_service();

		$args = array(
			'name'     => $name,
			'mimeType' => 'application/vnd.google-apps.folder',
		);

		// By default, folders will be created in the root directory, unless otherwise stated.
		if ( ! empty( $parent_id ) ) {
			$args['parents'] = array( $parent_id );
		}

		$folder = new Google_Service_Drive_DriveFile( $args );

		// Catch any possible exceptions thrown by Google Drive classes.
		try {
			$req = $service->files->create( $folder, array(
				'fields' => 'id',
			));

			return $req;
		} catch ( Google_Service_Exception $e ) {
			echo '<p>' . __( 'Error: Unable to create folder. Error code ', 'boldgrid-backup' ) . esc_html( $e->getCode() ) . '</p>'; // phpcs:ignore

			return false;
		}
	}

	/**
	 * Create our parent folder.
	 *
	 * @see self::parent_folder_name
	 *
	 * @return mixed Google_Service_Drive_DriveFile on Success, false on failure.
	 */
	private function create_parent() {
		return $this->create( $this->parent_folder_name );
	}

	/**
	 * Enforce retention.
	 *
	 * @since 1.1.0
	 *
	 * @param string $folder_id The id of the folder to enforce retention on.
	 */
	public function enforce_retention( $folder_id = '' ) {
		// If we have an invalid retention count, abort.
		$retention_count = $this->premium_core->google_drive->settings->get_setting( 'retention_count', $this->premium_core->google_drive->page->get_default_retention_count() );
		if ( empty( $retention_count ) ) {
			return;
		}

		$files = $this->get_files_asc( $folder_id );
		if ( count( $files ) <= $retention_count ) {
			return;
		}

		$service = $this->premium_core->google_drive->client->get_service();

		$count_to_delete = count( $files ) - $retention_count;
		foreach ( $files as $file ) {
			if ( 0 === $count_to_delete ) {
				break;
			}

			try {
				$service->files->delete( $file['id'] );
			} catch ( Exception $e ) {
				$this->last_error = __( 'An error occurred deleting a backup during retention:', 'boldgrid-backup' ) . ' ' . $e->getMessage();
			}

			$count_to_delete--;
		}
	}

	/**
	 * Get the id of our folder.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $name      The name of the folder to create.
	 * @param  string $parent_id Optional, a parent folder id.
	 * @return string            Our folder id, or false on failure.
	 */
	public function get_id( $name, $parent_id = '' ) {
		if ( empty( $name ) ) {
			return false;
		}

		$folder = $this->get_folder( $name, $parent_id );
		if ( false === $folder ) {
			return false;
		}

		if ( empty( $folder ) ) {
			$folder = $this->create( $name, $parent_id );
		}

		return empty( $folder->id ) ? false : $folder->id;
	}

	/**
	 * Get a folder based on key / value search.
	 *
	 * Typical method is to get folder by name.
	 *
	 * @since 1.1.0
	 *
	 * @link https://developers.google.com/drive/api/v3/search-parameters
	 *
	 * @param  string $name      The name of the folder to create.
	 * @param  string $parent_id Optional, a parent folder id.
	 * @return mixed             Google_Service_Drive_DriveFile Object on success, false on failure.
	 */
	public function get_folder( $name, $parent_id = '' ) {
		$service = $this->premium_core->google_drive->client->get_service();

		$this->premium_core->google_drive->client->set_defer( false );

		$query = array(
			'name = "' . $name . '"',
			'mimeType = "application/vnd.google-apps.folder"',
			'trashed = false',
		);

		// By default, we do not specify a parent folder id.
		if ( ! empty( $parent_id ) ) {
			$query[] = '"' . $parent_id . '" in parents';
		}

		try {
			/*
			 * Build our query to search for a folder.
			 *
			 * It is assumed that we will be using this method to search by folder name. If we later
			 * accept other parameters besides $key = 'name', we will need to build the additional
			 * logic below.
			 *
			 *  Example $files: https://pastebin.com/54rRb6ue.
			 */
			$files = $service->files->listFiles( array(
				'q'        => implode( ' and ', $query ),
				'pageSize' => 1,
			));

			return empty( $files->files ) ? array() : $files->files[0];
		} catch ( Google_Exception $e ) {
			$message = __( 'Unable to get backup folder.', 'boldgrid-backup' );
			if ( 401 === $e->getCode() ) {
				$message .= ' ' . __( 'Invalid Credentials.', 'boldgrid-backup' );
			}
			$this->last_error = $message;

			return false;
		}
	}

	/**
	 * Get the id of our backup folder.
	 *
	 * Do not confuse this with self::get_parent_id().
	 *
	 * /parent folder/backup folder/backup.zip
	 *
	 * @since 1.1.0
	 *
	 * @see self::get_parent_id()
	 * @see self::parent_folder_name
	 *
	 * @return string Our folder id, or false on failure.
	 */
	public function get_backup_id() {
		return $this->get_id( $this->get_folder_name(), $this->get_parent_id() );
	}

	/**
	 * Get a specific file.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $folder_id The id of the file's folder.
	 * @param  string $filename  The name of our file.
	 * @return array             The first file found.
	 */
	public function get_file( $folder_id = '', $filename ) {
		$service = $this->premium_core->google_drive->client->get_service();

		$this->premium_core->google_drive->client->set_defer( false );

		if ( empty( $folder_id ) ) {
			$folder_id = $this->get_backup_id();
			if ( false === $folder_id ) {
				$this->last_error = $this->last_error;
				return false;
			}
		}

		try {
			$query = array(
				'"' . $folder_id . '" in parents',
				'name = "' . $filename . '"',
				'mimeType != "application/vnd.google-apps.folder"',
				'trashed = false',
			);

			$files = $service->files->listFiles( array(
				'q'        => implode( ' AND ', $query ),
				'pageSize' => 1,
				'fields'   => 'files(id,size)',
			));

			// Return the first file in the set of results.
			return empty( $files->files ) ? array() : $files->files[0];
		} catch ( Google_Exception $e ) {
			// Translators: 1: Error message.
			$this->last_error = sprintf( __( 'Unable to determine if archive exists in Google Drive. %1$s.', 'boldgrid-backup' ), $e->getMessage() );

			return false;
		}
	}

	/**
	 * Get a list of files in our backup folder.
	 *
	 * @since 1.1.0
	 *
	 * @param  string $folder_id The folder id to get the contents of.
	 * @return mixed             False on failure, Google_Service_Drive_FileList on success.
	 *                           Example: https://pastebin.com/Ui0BSrwz
	 */
	public function get_files( $folder_id = '' ) {
		$folder_id = empty( $folder_id ) ? $this->get_backup_id() : $folder_id;
		if ( empty( $folder_id ) ) {
			$this->last_error = __( 'Unable to get Google Drive folder id.', 'boldgrid-backup' );
			return false;
		}

		$service = $this->premium_core->google_drive->client->get_service();

		$this->premium_core->google_drive->client->set_defer( false );

		$q = array(
			'"' . $folder_id . '" in parents',
			'mimeType != "application/vnd.google-apps.folder"',
			'trashed = false',
		);

		try {
			$files = $service->files->listFiles( array(
				'q'        => implode( ' and ', $q ),
				'pageSize' => 100,
				'fields'   => 'files(id,size,name,createdTime,properties)',
			));

			return $files;
		} catch ( Google_Exception $e ) {
			$message = __( 'Unable to retrieve file listing.', 'boldgrid-backup' );
			if ( 401 === $e->getCode() ) {
				$message .= ' ' . __( 'Invalid Credentials.', 'boldgrid-backup' );
			}

			$this->last_error = $message;

			return false;
		}
	}

	/**
	 * Get a list of files in asc order (based on date created).
	 *
	 * Used in the retention process.
	 *
	 * @param  string $folder_id The folder id to get the contents of.
	 * @return array
	 */
	public function get_files_asc( $folder_id = '' ) {
		$folder_id = empty( $folder_id ) ? $this->get_backup_id() : $folder_id;

		$files = $this->get_files( $folder_id );
		$files = $files instanceof Google_Service_Drive_FileList && ! empty( $files['files'] ) ? $files['files'] : array();

		// Sort by created time.
		usort( $files, function( $a, $b ) {
			$a_time = ! empty( $a['properties']['createdTime'] ) ? $a['properties']['createdTime'] : strtotime( $a['createdTime'] ); // phpcs:ignore
			$b_time = ! empty( $b['properties']['createdTime'] ) ? $b['properties']['createdTime'] : strtotime( $b['createdTime'] ); // phpcs:ignore

			// Sorts low (oldest) to high (newest).
			return $a_time > $b_time ? 1 : -1;
		});

		return $files;
	}

	/**
	 * Get the name of our folder on Google Drive where we are storing backups.
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_folder_name() {
		return $this->premium_core->google_drive->settings->get_setting( 'folder_name', $this->premium_core->google_drive->page->get_default_folder_name() );
	}

	/**
	 * Get our parent folder.
	 *
	 * @since 1.1.0
	 *
	 * @see self::parent_folder_name
	 *
	 * @return mixed Google_Service_Drive_DriveFile Object on success, false on failure.
	 */
	private function get_parent_folder() {
		return $this->get_folder( $this->parent_folder_name );
	}

	/**
	 * Get our parent folder id.
	 *
	 * Do not confuse this with self::get_backup_id().
	 *
	 * /parent folder/backup folder/backup.zip
	 *
	 * @since 1.1.0
	 *
	 * @see self::get_backup_id()
	 * @see self::parent_folder_name
	 *
	 * @return string Our folder id, or false on failure.
	 */
	public function get_parent_id() {
		return $this->get_id( $this->parent_folder_name );
	}
}
