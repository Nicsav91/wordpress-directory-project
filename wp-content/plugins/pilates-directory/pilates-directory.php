<?php
/**
 * Plugin Name: Pilates Directory Stockholm
 * Description: En directory-plugin för pilates-studios i Stockholm med sök, filter och recensioner
 * Version: 1.0
 * Author: Your Name
 * Text Domain: pilates-directory
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PD_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include required files
require_once PD_PLUGIN_PATH . 'includes/class-pilates-directory.php';
require_once PD_PLUGIN_PATH . 'includes/class-pilates-studio-post-type.php';
require_once PD_PLUGIN_PATH . 'includes/class-pilates-review.php';
require_once PD_PLUGIN_PATH . 'includes/class-pilates-shortcodes.php';
require_once PD_PLUGIN_PATH . 'includes/class-pilates-ajax.php';

// Initialize the plugin
function pilates_directory_init() {
    new Pilates_Directory();
}
add_action('plugins_loaded', 'pilates_directory_init');

// Activation hook
register_activation_hook(__FILE__, array('Pilates_Directory', 'activate'));

// Deactivation hook
register_deactivation_hook(__FILE__, array('Pilates_Directory', 'deactivate'));