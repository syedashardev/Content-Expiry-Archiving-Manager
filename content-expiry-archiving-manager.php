<?php
/**
 * Plugin Name: Content Expiry & Archiving Manager
 * Description: Manage the expiry and archiving of WordPress content with automatic actions and notifications.
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 1.0
 * Author: syedashardev
 * Text Domain: https://afashah.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants
define('CEAM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CEAM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once CEAM_PLUGIN_DIR . 'includes/class-ceam-expiry-manager.php';
require_once CEAM_PLUGIN_DIR . 'includes/class-ceam-notification-manager.php';
require_once CEAM_PLUGIN_DIR . 'includes/class-ceam-admin.php';

// Initialize the plugin
function ceam_init() {
    CEAM_Expiry_Manager::get_instance();
    CEAM_Notification_Manager::get_instance();
    CEAM_Admin::get_instance();
}
add_action('plugins_loaded', 'ceam_init');
