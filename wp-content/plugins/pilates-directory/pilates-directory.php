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
require_once PD_PLUGIN_PATH . 'includes/class-pilates-sample-data.php';

// Initialize the plugin
function pilates_directory_init() {
    new Pilates_Directory();
}
add_action('plugins_loaded', 'pilates_directory_init');

// Register post type early
add_action('init', function() {
    // Register Pilates Studio post type
    $labels = array(
        'name'                  => 'Pilates Studios',
        'singular_name'         => 'Pilates Studio',
        'menu_name'             => 'Pilates Studios',
        'name_admin_bar'        => 'Pilates Studio',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Studio',
        'new_item'              => 'New Studio',
        'edit_item'             => 'Edit Studio',
        'view_item'             => 'View Studio',
        'all_items'             => 'All Studios',
        'search_items'          => 'Search Studios',
        'not_found'             => 'Not found',
        'not_found_in_trash'    => 'Not found in Trash',
    );
    
    $args = array(
        'label'                 => 'Pilates Studio',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-location-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    
    register_post_type('pilates_studio', $args);
    
    // Register taxonomies
    register_taxonomy('studio_area', 'pilates_studio', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Områden',
            'singular_name' => 'Område',
            'menu_name' => 'Områden',
        ),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'område'),
    ));
    
    register_taxonomy('price_class', 'pilates_studio', array(
        'hierarchical' => false,
        'labels' => array(
            'name' => 'Prisklass',
            'singular_name' => 'Prisklass',
            'menu_name' => 'Prisklass',
        ),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'prisklass'),
    ));
    
    register_taxonomy('specialties', 'pilates_studio', array(
        'hierarchical' => false,
        'labels' => array(
            'name' => 'Specialiteter',
            'singular_name' => 'Specialitet',
            'menu_name' => 'Specialiteter',
        ),
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'specialiteter'),
    ));
}, 10);

// Force flush rewrite rules on admin init if needed
add_action('admin_init', function() {
    if (get_option('pilates_directory_flush_rewrite_rules', false)) {
        flush_rewrite_rules();
        delete_option('pilates_directory_flush_rewrite_rules');
    }
});

// Set flush rewrite rules flag on plugin activation  
register_activation_hook(__FILE__, function() {
    add_option('pilates_directory_flush_rewrite_rules', true);
    
    // Force create post type immediately
    require_once PD_PLUGIN_PATH . 'includes/class-pilates-studio-post-type.php';
    new Pilates_Studio_Post_Type();
    flush_rewrite_rules();
});

// Activation hook
register_activation_hook(__FILE__, array('Pilates_Directory', 'activate'));

// Deactivation hook
register_deactivation_hook(__FILE__, array('Pilates_Directory', 'deactivate'));