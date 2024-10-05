<?php
if (file_exists(ABSPATH . 'wp-content/htaccess.backup')) {
    echo '<div class="postbox htaccess-file-editor-box" style="background: #FFEECE;">';
    ?>
    <form method="post"
          action="<?php echo esc_url( admin_url('admin.php?page=htaccess-file-editor-backup') ); ?>">
        <?php wp_nonce_field('htaccess_file_editor_restoreb', 'htaccess_file_editor_restoreb'); ?>
        <input type="hidden" name="restore_backup" value="restore"/>
        <p class="submit"><?php esc_html_e('Do you want to restore the backup file?', 'htaccess-file-editor'); ?>
            <input
                    type="submit" class="button button-primary" name="submit"
                    value="<?php esc_attr_e('Restore backup &raquo;', 'htaccess-file-editor'); ?>"/></p>
    </form>
    <?php
    echo '</div>';
    echo '<div class="postbox htaccess-file-editor-box" style="background: #FFEECE;">';
    ?>
    <form method="post" action="<?php echo esc_url( admin_url('admin.php?page=htaccess-file-editor-backup') ); ?>">
        <?php wp_nonce_field('htaccess_file_editor_deleteb', 'htaccess_file_editor_deleteb'); ?>
        <input type="hidden" name="delete_backup" value="delete"/>
        <p class="submit"><?php esc_html__('Do you want to delete a backup file?', 'htaccess-file-editor'); ?>
            <input
                    type="submit" class="button button-primary" name="submit"
                    value="<?php esc_attr_e('Remove backup &raquo;', 'htaccess-file-editor'); ?>"/></p>
    </form>
    <?php
    echo '</div>';
} else {
    echo '<div class="postbox htaccess-file-editor-box">';
    echo '<pre class="htaccess-file-editor-red">' . esc_html__('Backup file not found...', 'htaccess-file-editor') . '</pre>';
    echo '</div>';

    echo '<div class="postbox htaccess-file-editor-box" style="background: #E0FCE1;">';
    ?>
    <form method="post" action="<?php echo esc_url( admin_url('admin.php?page=htaccess-file-editor-backup' ) ); ?>">
        <?php wp_nonce_field('htaccess_file_editor_createb', 'htaccess_file_editor_createb'); ?>
        <input type="hidden" name="create_backup" value="create"/>
        <p class="submit"><?php esc_html_e('Do you want to create a new backup file?', 'htaccess-file-editor'); ?>
            <input
                    type="submit" class="button button-primary" name="submit"
                    value="<?php esc_attr_e('Create new &raquo;', 'htaccess-file-editor'); ?>"/></p>
    </form>
    <?php
    echo '</div>';
}