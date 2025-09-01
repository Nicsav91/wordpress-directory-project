<?php

class Pilates_Directory {
    
    public function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    
    public function init() {
        // Initialize post types
        new Pilates_Studio_Post_Type();
        
        // Initialize reviews
        new Pilates_Review();
        
        // Initialize shortcodes
        new Pilates_Shortcodes();
        
        // Initialize AJAX handlers
        new Pilates_Ajax();
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('pilates-directory-style', PD_PLUGIN_URL . 'assets/css/style.css', array(), '1.0');
        wp_enqueue_script('pilates-directory-script', PD_PLUGIN_URL . 'assets/js/script.js', array('jquery'), '1.0', true);
        
        // Localize script for AJAX
        wp_localize_script('pilates-directory-script', 'pilates_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pilates_nonce')
        ));
        
        // Google Maps API - For demo purposes using a test key
        // För produktion, lägg till din egen API-nyckel i wp-config.php som: define('GOOGLE_MAPS_API_KEY', 'din_nyckel');
        $api_key = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : '';
        if ($api_key) {
            wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places&callback=initPilatesMap', array(), null, true);
        }
    }
    
    public function admin_enqueue_scripts($hook) {
        global $post_type;
        if ($post_type == 'pilates_studio') {
            wp_enqueue_style('pilates-directory-admin', PD_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0');
            wp_enqueue_script('pilates-directory-admin', PD_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), '1.0', true);
        }
    }
    
    public static function activate() {
        // Initialize post types
        new Pilates_Studio_Post_Type();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Create necessary database tables
        self::create_tables();
    }
    
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    private static function create_tables() {
        global $wpdb;
        
        $reviews_table = $wpdb->prefix . 'pilates_reviews';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $reviews_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            studio_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            rating tinyint(1) NOT NULL,
            comment text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}