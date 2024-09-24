<?php
/*
Plugin Name: Htaccess File Editor
Plugin URI: https://wpchill.com/
Description: Simple editor for htaccess file without using FTP client.
Version: 1.0.18
Text Domain: htaccess-file-editor
Author: WPChill
Author URI: https://wpchill.com/
Requires at least: 3.0.0
Tested up to: 6.6
License: GPLv2 or later
* Copyright 2024            WPChill             heyyy@wpchill.com
*
* Original Plugin URI:      https://mantrabrain.com/
* Original Author URI:      https://mantrabrain.com/
* Original Author:          https://profiles.wordpress.org/mantrabrain/
*
* MantraBrain has transferred ownership to WPChill on August 2024.
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License, version 3, as
* published by the Free Software Foundation.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// Define HTACCESS_FILE_EDITOR_PLUGIN_FILE.
if (!defined('HTACCESS_FILE_EDITOR_FILE')) {
    define('HTACCESS_FILE_EDITOR_FILE', __FILE__);
}

// Define HTACCESS_FILE_EDITOR_VERSION.
if (!defined('HTACCESS_FILE_EDITOR_VERSION')) {
    define('HTACCESS_FILE_EDITOR_VERSION', '1.0.17');
}

// Define HTACCESS_FILE_EDITOR_PLUGIN_URI.
if (!defined('HTACCESS_FILE_EDITOR_PLUGIN_URI')) {
    define('HTACCESS_FILE_EDITOR_PLUGIN_URI', plugins_url('', HTACCESS_FILE_EDITOR_FILE));
}

// Define HTACCESS_FILE_EDITOR_PLUGIN_DIR.
if (!defined('HTACCESS_FILE_EDITOR_PLUGIN_DIR')) {
    define('HTACCESS_FILE_EDITOR_PLUGIN_DIR', plugin_dir_path(HTACCESS_FILE_EDITOR_FILE));
}


// Include the main Htaccess_File_Editor class.
if (!class_exists('Htaccess_File_Editor')) {
    include_once dirname(__FILE__) . '/includes/class-htaccess-file-editor.php';
}


/**
 * Main instance of Htaccess_File_Editor.
 *
 * Returns the main instance of Htaccess_File_Editor to prevent the need to use globals.
 *
 * @return Htaccess_File_Editor
 * @since  1.0.0
 */
function htaccess_file_editor()
{
    return Htaccess_File_Editor::instance();
}

// Global for backwards compatibility.
$GLOBALS['htaccess_file_editor_instance'] = htaccess_file_editor();