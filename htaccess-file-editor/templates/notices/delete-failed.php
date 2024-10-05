<?php
echo '<div id="message" class="error fade"><p><strong>' . esc_html__('Backup file could not be removed! Probably the wrong setting write permissions to the files.', 'htaccess-file-editor') . '</strong></p></div>';
echo '<div id="message" class="error fade"><p><strong>' . esc_html__('Due to server configuration can not change permissions on files or create new files', 'htaccess-file-editor') . '</strong></p></div>';
