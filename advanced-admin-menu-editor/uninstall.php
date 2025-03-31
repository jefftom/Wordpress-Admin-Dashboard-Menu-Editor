<?php
/**
 * Uninstall file for Advanced Admin Menu Editor
 * Fired when the plugin is uninstalled
 *
 * @package Multiple_Menus_Admin_Editor
 */

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('came_menu_configs');
delete_option('came_theme_settings');

// Delete user meta for all users
global $wpdb;
$wpdb->delete($wpdb->usermeta, array('meta_key' => 'came_active_config'));

// Clear any related transients
delete_transient('came_admin_menu_structure');

// Log the uninstallation (for debugging only)
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Advanced Admin Menu Editor: Plugin uninstalled and data removed');
}