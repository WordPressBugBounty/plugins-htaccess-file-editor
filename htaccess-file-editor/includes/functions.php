<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You do not have sufficient permissions to access this file.' );
}

function htaccess_file_editor_create_backup() {
	// Rename the old backup file to the new name using WP_Filesystem
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();
	$saved_name = get_option( 'htaccess_file_editor_backup_name' );
	// Check if the backup file is saved in the options
	if ( $saved_name ) {
		$file_name = $saved_name;
	} else {
		$hash      = sanitize_file_name( substr( wp_generate_password( 10, false ), 0, 5 ) );
		$file_name = '.htaccess-file-editor-bkup-' . $hash;
	}
	$WPHE_backup_path = ABSPATH . 'wp-content/' . $file_name;
	$WPHE_orig_path   = ABSPATH . '.htaccess';
	@clearstatcache();

	htaccess_file_editor_create_secure_wpcontent( $file_name );
	if ( file_exists( $WPHE_backup_path ) ) {
		htaccess_file_editor_delete_backup( $file_name );

		if ( file_exists( ABSPATH . '.htaccess' ) ) {
			$htaccess_content_orig = $wp_filesystem->get_contents( $WPHE_orig_path );
			$htaccess_content_orig = trim( $htaccess_content_orig );
			$htaccess_content_orig = str_replace( '\\\\', '\\', $htaccess_content_orig );
			$htaccess_content_orig = str_replace( '\"', '"', $htaccess_content_orig );
			$wp_filesystem->chmod( $WPHE_backup_path, 0666 );
			$WPHE_success = $wp_filesystem->put_contents( $WPHE_backup_path, $htaccess_content_orig );
			if ( $WPHE_success === false ) {
				unset( $WPHE_backup_path );
				unset( $WPHE_orig_path );
				unset( $htaccess_content_orig );
				unset( $WPHE_success );
				return false;
			} else {
				unset( $WPHE_backup_path );
				unset( $WPHE_orig_path );
				unset( $htaccess_content_orig );
				unset( $WPHE_success );
				update_option( 'htaccess_file_editor_backup_name', $file_name );
				return true;
			}
			$wp_filesystem->chmod( $WPHE_backup_path, 0644 );
		} else {
			unset( $WPHE_backup_path );
			unset( $WPHE_orig_path );
			return false;
		}
	} elseif ( file_exists( ABSPATH . '.htaccess' ) ) {
		$htaccess_content_orig = $wp_filesystem->get_contents( $WPHE_orig_path );
		$htaccess_content_orig = trim( $htaccess_content_orig );
		$htaccess_content_orig = str_replace( '\\\\', '\\', $htaccess_content_orig );
		$htaccess_content_orig = str_replace( '\"', '"', $htaccess_content_orig );
		if ( file_exists( $WPHE_backup_path ) ) {
			$wp_filesystem->chmod( $WPHE_backup_path, 0666 );
		}

		$WPHE_success = $wp_filesystem->put_contents( $WPHE_backup_path, $htaccess_content_orig );
		if ( $WPHE_success === false ) {
			unset( $WPHE_backup_path );
			unset( $WPHE_orig_path );
			unset( $htaccess_content_orig );
			unset( $WPHE_success );
			return false;
		} else {
			unset( $WPHE_backup_path );
			unset( $WPHE_orig_path );
			unset( $htaccess_content_orig );
			unset( $WPHE_success );
			update_option( 'htaccess_file_editor_backup_name', $file_name );
			return true;
		}
		$wp_filesystem->chmod( $WPHE_backup_path, 0644 );
	} else {
		unset( $WPHE_backup_path );
		unset( $WPHE_orig_path );
		return false;
	}
}


function htaccess_file_editor_create_secure_wpcontent( $file_name = false ) {
	if ( ! $file_name ) {
		return false;
	}
	$htaccess_file_editor_secure_path = ABSPATH . 'wp-content/.htaccess';
	$htaccess_file_editor_secure_text = '
# Htaccess File Editor - Secure backups
<files ' . $file_name . '>
order allow,deny
deny from all
</files>
';
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();
	if ( is_readable( ABSPATH . 'wp-content/.htaccess' ) ) {
		$htaccess_file_editor_secure_content = $wp_filesystem->get_contents( ABSPATH . 'wp-content/.htaccess' );

		if ( $htaccess_file_editor_secure_content !== false ) {
			if ( strpos( $htaccess_file_editor_secure_content, '<files ' . $file_name . '>' ) === false ) {
				unset( $htaccess_file_editor_secure_content );
				$htaccess_file_editor_create_sec = $wp_filesystem->put_contents( ABSPATH . 'wp-content/.htaccess', $htaccess_file_editor_secure_text );
				if ( $htaccess_file_editor_create_sec !== false ) {
					unset( $htaccess_file_editor_secure_text );
					unset( $htaccess_file_editor_create_sec );
					return true;
				} else {
					unset( $htaccess_file_editor_secure_text );
					unset( $htaccess_file_editor_create_sec );
					return false;
				}
			} else {
				unset( $htaccess_file_editor_secure_content );
				return true;
			}
		} else {
			unset( $htaccess_file_editor_secure_content );
			return false;
		}
	} elseif ( file_exists( ABSPATH . 'wp-content/.htaccess' ) ) {
			return false;
	} else {
		$htaccess_file_editor_create_sec = $wp_filesystem->put_contents( ABSPATH . 'wp-content/.htaccess', $htaccess_file_editor_secure_text );
		if ( $htaccess_file_editor_create_sec !== false ) {
			return true;
		} else {
			return false;
		}
	}
}


function htaccess_file_editor_restore_backup() {
	$file_name = get_option( 'htaccess_file_editor_backup_name' );
	if ( ! $file_name ) {
		return false;
	}
	$htaccess_file_editor_backup_path = ABSPATH . 'wp-content/' . $file_name;
	$WPHE_orig_path                   = ABSPATH . '.htaccess';
	@clearstatcache();
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();

	if ( ! file_exists( $htaccess_file_editor_backup_path ) ) {
		unset( $htaccess_file_editor_backup_path );
		unset( $WPHE_orig_path );
		return false;
	} else {
		if ( file_exists( $WPHE_orig_path ) ) {
			if ( $wp_filesystem->is_writable( $WPHE_orig_path ) ) {
				wp_delete_file( $WPHE_orig_path );
			} else {
				$wp_filesystem->chmod( $WPHE_orig_path, 0666 );
				wp_delete_file( $WPHE_orig_path );
			}
		}
		$htaccess_file_editor_htaccess_content_backup = $wp_filesystem->get_contents( $htaccess_file_editor_backup_path );
		if ( htaccess_file_editor_write_new_htaccess( $htaccess_file_editor_htaccess_content_backup ) === false ) {
			unset( $htaccess_file_editor_success );
			unset( $WPHE_orig_path );
			unset( $htaccess_file_editor_backup_path );
			return $htaccess_file_editor_htaccess_content_backup;
		} else {
			htaccess_file_editor_delete_backup( $file_name );
			unset( $htaccess_file_editor_success );
			unset( $htaccess_file_editor_htaccess_content_backup );
			unset( $WPHE_orig_path );
			unset( $htaccess_file_editor_backup_path );
			return true;
		}
	}
}


function htaccess_file_editor_delete_backup( $file_name = false ) {

	if ( ! $file_name ) {
		$file_name = get_option( 'htaccess_file_editor_backup_name' );
		if ( ! $file_name ) {
			return false;
		}
	}
	$htaccess_file_editor_backup_path = ABSPATH . 'wp-content/' . $file_name;
	@clearstatcache();

	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();
	if ( file_exists( $htaccess_file_editor_backup_path ) ) {
		if ( $wp_filesystem->is_writable( $htaccess_file_editor_backup_path ) ) {
			wp_delete_file( $htaccess_file_editor_backup_path );
		} else {
			$wp_filesystem->chmod( $htaccess_file_editor_backup_path, 0666 );
			wp_delete_file( $htaccess_file_editor_backup_path );
		}

		@clearstatcache();

		if ( file_exists( $htaccess_file_editor_backup_path ) ) {
			unset( $htaccess_file_editor_backup_path );
			return false;
		} else {
			delete_option( 'htaccess_file_editor_backup_name' );
			unset( $htaccess_file_editor_backup_path );
			return true;
		}
	} else {
		delete_option( 'htaccess_file_editor_backup_name' );
		unset( $htaccess_file_editor_backup_path );
		return true;
	}
}


function htaccess_file_editor_write_new_htaccess( $WPHE_new_content ) {
	$WPHE_orig_path = ABSPATH . '.htaccess';
	@clearstatcache();
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();

	if ( file_exists( $WPHE_orig_path ) ) {
		if ( $wp_filesystem->is_writable( $WPHE_orig_path ) ) {
			wp_delete_file( $WPHE_orig_path );
		} else {
			$wp_filesystem->chmod( $WPHE_orig_path, 0666 );
			wp_delete_file( $WPHE_orig_path );
		}
	}
	$WPHE_new_content   = trim( $WPHE_new_content );
	$WPHE_new_content   = str_replace( '\\\\', '\\', $WPHE_new_content );
	$WPHE_new_content   = str_replace( '\"', '"', $WPHE_new_content );
	$WPHE_write_success = $wp_filesystem->put_contents( $WPHE_orig_path, $WPHE_new_content );
	@clearstatcache();
	if ( ! file_exists( $WPHE_orig_path ) && $WPHE_write_success === false ) {
		unset( $WPHE_orig_path );
		unset( $WPHE_new_content );
		unset( $WPHE_write_success );
		return false;
	} else {
		unset( $WPHE_orig_path );
		unset( $WPHE_new_content );
		unset( $WPHE_write_success );
		return true;
	}
}


function htaccess_file_editor_debug( $data ) {
	echo '<pre>';
	var_dump( $data );
	echo '</pre>';
}
