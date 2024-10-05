<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You do not have sufficient permissions to access this file.' );
}
if ( ! current_user_can( 'activate_plugins' ) ) {
	echo esc_html__( 'You do not have permission to view this page', 'htaccess-file-editor' );
}

$htaccess_file_editor_backup_path = WP_CONTENT_URL . '/htaccess.backup';
$htaccess_file_editor_origin_path = ABSPATH . '.htaccess';
?>
	<div class="wrap">
		<h2 class="htaccess-file-editor-title" style="padding-left:50px;">Htaccess File Editor</h2>
		<?php
		// ============================ Uložení Htaccess souboru =======================================
		if ( ! empty( $_POST['submit'] ) and ! empty( $_POST['save_htaccess'] ) and check_admin_referer( 'htaccess_file_editor_save', 'htaccess_file_editor_save' ) ) {
			$WPHE_new_content = $_POST['ht_content'];
			htaccess_file_editor_delete_backup();
			if ( htaccess_file_editor_create_backup() ) {
				if ( htaccess_file_editor_write_new_htaccess( $WPHE_new_content ) ) {
					echo '<div id="message" class="updated fade"><p><strong>' . esc_html__( 'File has been successfully changed', 'htaccess-file-editor' ) . '</strong></p></div>';
					?>
					<p><?php esc_html_e( 'You have made changes to the htaccess file. The original file was automatically backed up (in <code>wp-content</code> folder)', 'htaccess-file-editor' ); ?>
						<br/>
						<a href="<?php echo esc_url( get_option( 'home' ) ); ?>/"
						   target="_blank"><?php esc_html_e( 'Check the functionality of your site (the links to the articles or categories).', 'htaccess-file-editor' ); ?></a>. <?php esc_html_e( 'If something is not working properly restore the original file from backup', 'htaccess-file-editor' ); ?>
					</p>
					<div class="postbox" style="float: left; width: 95%; padding: 15px;">
						<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=htaccess-file-editor' ) ); ?>">
							<?php wp_nonce_field( 'htaccess_file_editor_delete', 'htaccess_file_editor_delete' ); ?>
							<input type="hidden" name="delete_backup" value="delete"/>
							<p class="submit"><?php esc_attr_e( 'If everything works properly, you can delete the backup file:', 'htaccess-file-editor' ); ?>
								<input type="submit" class="button button-primary" name="submit"
									   value="<?php esc_attr_e( 'Remove backup &raquo;', 'htaccess-file-editor' ); ?>"/>&nbsp;<?php echo esc_html__( 'or', 'htaccess-file-editor' ); ?>
								&nbsp;<a
										href="<?php echo esc_url( admin_url( 'admin.php?page=htaccess-file-editor-backup' ) ); ?>"><?php esc_html_e( 'restore the original file from backup', 'htaccess-file-editor' ); ?></a>
							</p>
						</form>
					</div>
					<?php
				} else {
					echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'The file could not be saved!', 'htaccess-file-editor' ) . '</strong></p></div>';
					echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'Due to server configuration can not change permissions on files or create new files', 'htaccess-file-editor' ) . '</strong></p></div>';
				}
			} else {
				echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'The file could not be saved!', 'htaccess-file-editor' ) . '</strong></p></div>';
				echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'Unable to create backup of the original file! <code>wp-content</code> folder is not writeable! Change the permissions this folder!', 'htaccess-file-editor' ) . '</strong></p></div>';
			}
			unset( $WPHE_new_content );
			// ============================ Vytvoření nového Htaccess souboru ================================
		} elseif ( ! empty( $_POST['submit'] ) and ! empty( $_POST['create_htaccess'] ) and check_admin_referer( 'htaccess_file_editor_create', 'htaccess_file_editor_create' ) ) {
			if ( htaccess_file_editor_write_new_htaccess( '# Created by Htaccess File Editor' ) === false ) {
				echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'Htaccess file is not created.', 'htaccess-file-editor' ) . '</p></div>';
				echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'Due to server configuration can not change permissions on files or create new files', 'htaccess-file-editor' ) . '</strong></p></div>';
			} else {
				echo '<div id="message" class="updated fade"><p><strong>' . esc_html__( 'Htaccess file was successfully created.', 'htaccess-file-editor' ) . '</strong></p></div>';
				echo '<div id="message" class="updated fade"><p><strong><a href="' . esc_url( admin_url( 'admin.php?page=htaccess-file-editor' ) ) . '">' . esc_html__( 'View new Htaccess file', 'htaccess-file-editor' ) . '</a></strong></p></div>';
			}
			// ============================ Smazání zálohy =======================================
		} elseif ( ! empty( $_POST['submit'] ) and ! empty( $_POST['delete_backup'] ) and check_admin_referer( 'htaccess_file_editor_delete', 'htaccess_file_editor_delete' ) ) {
			if ( htaccess_file_editor_delete_backup() === false ) {
				echo '<div id="message" class="error fade"><p><strong>' . esc_html__( 'Backup file could not be removed! <code>wp-content</code> folder is not writeable! Change the permissions this folder!', 'htaccess-file-editor' ) . '</p></div>';
			} else {
				echo '<div id="message" class="updated fade"><p><strong>' . esc_html__( 'Backup file has been successfully removed.', 'htaccess-file-editor' ) . '</strong></p></div>';
			}
			// ============================ Home ================================================
		} else {
			?>
			<p><?php esc_html_e( 'Using this editor you can easily modify your htaccess file without having to use an FTP client.', 'htaccess-file-editor' ); ?></p>
			<p class="htaccess-file-editor-red"><?php echo wp_kses_post( '<strong>WARNING:</strong> Any error in this file may cause malfunction of your site!', 'htaccess-file-editor' ); ?>
				<br/>
				<?php esc_html_e( 'Edit htaccess file should therefore be performed only by experienced users!', 'htaccess-file-editor' ); ?><br/>
			</p>
			<div class="postbox htaccess-file-editor-box">
				<h3 class="htaccess-file-editor-title"><?php esc_html_e( 'Information for editing htaccess file', 'htaccess-file-editor' ); ?></h3>
				<p><?php esc_html_e( 'For more information on possible adjustments to this file, please visit', 'htaccess-file-editor' ); ?> <a
							href="http://httpd.apache.org/docs/current/howto/htaccess.html" target="_blank">Apache
						Tutorial: .htaccess files</a> <?php esc_html_e( 'or', 'htaccess-file-editor' ); ?> <a
							href="http://net.tutsplus.com/tutorials/other/the-ultimate-guide-to-htaccess-files/"
							target="_blank">The Ultimate Guide to .htaccess Files</a>. </p>
				<p><a href="http://www.google.com/#sclient=psy&q=htaccess+how+to"
					  target="_blank"><?php esc_html_e( 'use the Google search.', 'htaccess-file-editor' ); ?></a></p>
			</div>
			<?php
			if ( ! file_exists( $htaccess_file_editor_origin_path ) ) {
				echo '<div class="postbox htaccess-file-editor-box">';
				echo '<pre class="htaccess-file-editor-red">' . esc_html__( 'Htaccess file does not exists!', 'htaccess-file-editor' ) . '</pre>';
				echo '</div>';
				$success = false;
			} else {
				$success = true;
				if ( ! is_readable( $htaccess_file_editor_origin_path ) ) {
					echo '<div class="postbox htaccess-file-editor-box">';
					echo '<pre class="htaccess-file-editor-red">' . esc_html__( 'Htaccess file cannot read!', 'htaccess-file-editor' ) . '</pre>';
					echo '</div>';
					$success = false;
				}
				if ( $success == true ) {
					@chmod( $htaccess_file_editor_origin_path, 0644 );
					$WPHE_htaccess_content = @file_get_contents( $htaccess_file_editor_origin_path, false, null );
					if ( $WPHE_htaccess_content === false ) {
						echo '<div class="postbox htaccess-file-editor-box">';
						echo '<pre class="htaccess-file-editor-red">' . esc_html__( 'Htaccess file cannot read!', 'htaccess-file-editor' ) . '</pre>';
						echo '</div>';
						$success = false;
					} else {
						$success = true;
					}
				}
			}

			if ( $success == true ) {
				?>
				<div class="postbox htaccess-file-editor-box">
					<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=htaccess-file-editor' ) ); ?>">
						<input type="hidden" name="save_htaccess" value="save"/>
						<?php wp_nonce_field( 'htaccess_file_editor_save', 'htaccess_file_editor_save' ); ?>
						<h3 class="htaccess-file-editor-title"><?php esc_html_e( 'Content of the Htaccess file', 'htaccess-file-editor' ); ?></h3>
						<textarea name="ht_content" class="htaccess-file-editor-textarea"
								  wrap="off" id="htaccess-file-editor-textarea"
								  aria-describedby="editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4"><?php echo $WPHE_htaccess_content; ?></textarea>
						<p class="submit"><input type="submit" class="button button-primary" name="submit"
												 value="<?php esc_html_e( 'Save file &raquo;', 'htaccess-file-editor' ); ?>"/></p>
					</form>
				</div>
				<?php
				unset( $WPHE_htaccess_content );
			} else {
				?>
				<div class="postbox htaccess-file-editor-box" style="background: #E0FCE1;">
					<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=htaccess-file-editor' ) ); ?>">
						<input type="hidden" name="create_htaccess" value="create"/>
						<?php wp_nonce_field( 'htaccess_file_editor_create', 'htaccess_file_editor_create' ); ?>
						<p class="submit"><?php esc_html__( 'Create new <code>.htaccess</code> file?', 'htaccess-file-editor' ); ?> <input
									type="submit" class="button button-primary" name="submit"
									value="<?php esc_attr_e( 'Create &raquo;', 'htaccess-file-editor' ); ?>"/></p>
					</form>
				</div>
				<?php
			}
			unset( $success );
		}
		?>
	</div>
<?php
unset( $htaccess_file_editor_origin_path );
unset( $htaccess_file_editor_backup_path );

