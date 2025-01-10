<?php

class Htaccess_File_Editor_Hooks {


	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 10 );
		add_action( 'admin_init', array( $this, 'migrate_backup_name' ) );
	}

	/**
	 * Load template file with our defined wrapper for sidebar.
	 *
	 * @param string $file
	 * @return void
	 */
	protected function template_loader( $file ) {
		?>
		<div class="htaccess-file-editor-main-container">

			<?php
				include_once $file;
				do_action( 'htaccess_file_editor_after_ebwp_banner' );
			?>
		</div>
		<?php
	}

	function admin_menu() {
		if ( current_user_can( 'activate_plugins' ) ) {
			add_menu_page( 'Htaccess File Editor', 'Htaccess', 'activate_plugins', 'htaccess-file-editor', array( $this, 'dashboard' ), HTACCESS_FILE_EDITOR_PLUGIN_URI . '/assets/images/icon.png' );

			add_submenu_page( 'htaccess-file-editor', 'Backup', 'Backup', 'activate_plugins', 'htaccess-file-editor-backup', array( $this, 'backup_page' ) );
		}
	}

	function scripts( $hooks ) {
		if ( $hooks != 'toplevel_page_htaccess-file-editor' && $hooks != 'htaccess_page_htaccess-file-editor-backup' ) {
			return;
		}
		wp_enqueue_style( 'htaccess-file-editor-style', HTACCESS_FILE_EDITOR_PLUGIN_URI . '/assets/css/admin.css', array( 'wp-codemirror' ), HTACCESS_FILE_EDITOR_VERSION );

		wp_enqueue_script( 'htaccess-file-editor-script', HTACCESS_FILE_EDITOR_PLUGIN_URI . '/assets/js/htaccess-file-editor.js', array( 'jquery', 'wp-theme-plugin-editor' ), HTACCESS_FILE_EDITOR_VERSION, true );

		$settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

		wp_localize_script( 'htaccess-file-editor-script', 'htaccess_file_editor_settings', $settings );
	}

	public function backup_page() {
		$this->template_loader( HTACCESS_FILE_EDITOR_ABSPATH . '/templates/backup-page.php' );
	}

	public function dashboard() {
		$this->template_loader( HTACCESS_FILE_EDITOR_ABSPATH . '/templates/dashboard.php' );
	}

	/**
	 * Migrate backup name from old version to new version
	 *
	 * @return void
	 */
	public function migrate_backup_name() {
		$old_name = 'htaccess.backup';
		$hash     = sanitize_file_name( substr( wp_generate_password( 10, false ), 0, 5 ) );
		$new_name = '.htaccess-file-editor-bkup-' . $hash;
		$path     = WP_CONTENT_DIR;
		// Retrieve the saved backup name
		$saved_name = get_option( 'htaccess_file_editor_backup_name' );
		// If the backup file is saved in the options and exists, then we don't need to migrate
		if ( $saved_name && file_exists( $path . '/' . $saved_name ) ) {
			return;
		}

		// If the old file doesn't exist, then we don't need to migrate
		if ( ! file_exists( $path . '/' . $old_name ) ) {
			return;
		}

		// Rename the old backup file to the new name using WP_Filesystem
		global $wp_filesystem;
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
		$wp_filesystem->move( $path . '/' . $old_name, $path . '/' . $new_name );
		update_option( 'htaccess_file_editor_backup_name', $new_name );
	}
}

new Htaccess_File_Editor_Hooks();
