<?php
echo '<div id="message" class="error fade"><p><strong>' . esc_html__('Unable to create backup! <code>wp-content</code> folder is not writeable! Change the permissions this folder manually!', 'htaccess-file-editor') . '</strong></p></div>';
echo '<div id="message" class="error fade"><p><strong>' . esc_html__('Due to server configuration can not change permissions on files or create new files', 'htaccess-file-editor') . '</strong></p></div>';
