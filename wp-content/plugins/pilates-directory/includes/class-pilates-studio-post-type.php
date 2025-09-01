<?php

class Pilates_Studio_Post_Type {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
    }
    
    public function register_post_type() {
        $labels = array(
            'name'                  => 'Pilates Studios',
            'singular_name'         => 'Pilates Studio',
            'menu_name'             => 'Pilates Studios',
            'name_admin_bar'        => 'Pilates Studio',
            'archives'              => 'Studio Archives',
            'attributes'            => 'Studio Attributes',
            'parent_item_colon'     => 'Parent Studio:',
            'all_items'             => 'All Studios',
            'add_new_item'          => 'Add New Studio',
            'add_new'               => 'Add New',
            'new_item'              => 'New Studio',
            'edit_item'             => 'Edit Studio',
            'update_item'           => 'Update Studio',
            'view_item'             => 'View Studio',
            'view_items'            => 'View Studios',
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
    }
    
    public function register_taxonomies() {
        // Område/Stadsdel taxonomy
        register_taxonomy('studio_area', 'pilates_studio', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Områden',
                'singular_name' => 'Område',
                'search_items' => 'Sök Områden',
                'all_items' => 'Alla Områden',
                'parent_item' => 'Överordnat Område',
                'parent_item_colon' => 'Överordnat Område:',
                'edit_item' => 'Redigera Område',
                'update_item' => 'Uppdatera Område',
                'add_new_item' => 'Lägg till Nytt Område',
                'new_item_name' => 'Nytt Områdesnamn',
                'menu_name' => 'Områden',
            ),
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'område'),
        ));
        
        // Prisklass taxonomy
        register_taxonomy('price_class', 'pilates_studio', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Prisklass',
                'singular_name' => 'Prisklass',
                'search_items' => 'Sök Prisklass',
                'all_items' => 'Alla Prisklasser',
                'edit_item' => 'Redigera Prisklass',
                'update_item' => 'Uppdatera Prisklass',
                'add_new_item' => 'Lägg till Ny Prisklass',
                'new_item_name' => 'Nytt Prisklassnamn',
                'menu_name' => 'Prisklass',
            ),
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'prisklass'),
        ));
        
        // Specialiteter taxonomy
        register_taxonomy('specialties', 'pilates_studio', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Specialiteter',
                'singular_name' => 'Specialitet',
                'search_items' => 'Sök Specialiteter',
                'all_items' => 'Alla Specialiteter',
                'edit_item' => 'Redigera Specialitet',
                'update_item' => 'Uppdatera Specialitet',
                'add_new_item' => 'Lägg till Ny Specialitet',
                'new_item_name' => 'Nytt Specialitetsnamn',
                'menu_name' => 'Specialiteter',
            ),
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'specialiteter'),
        ));
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'pilates_studio_details',
            'Studio Details',
            array($this, 'studio_details_meta_box'),
            'pilates_studio',
            'normal',
            'high'
        );
    }
    
    public function studio_details_meta_box($post) {
        wp_nonce_field('pilates_studio_meta_box', 'pilates_studio_nonce');
        
        $address = get_post_meta($post->ID, '_studio_address', true);
        $phone = get_post_meta($post->ID, '_studio_phone', true);
        $email = get_post_meta($post->ID, '_studio_email', true);
        $website = get_post_meta($post->ID, '_studio_website', true);
        $price_range = get_post_meta($post->ID, '_studio_price_range', true);
        $latitude = get_post_meta($post->ID, '_studio_latitude', true);
        $longitude = get_post_meta($post->ID, '_studio_longitude', true);
        
        // Opening hours
        $opening_hours = get_post_meta($post->ID, '_studio_opening_hours', true);
        if (!$opening_hours) {
            $opening_hours = array(
                'monday' => array('open' => '', 'close' => ''),
                'tuesday' => array('open' => '', 'close' => ''),
                'wednesday' => array('open' => '', 'close' => ''),
                'thursday' => array('open' => '', 'close' => ''),
                'friday' => array('open' => '', 'close' => ''),
                'saturday' => array('open' => '', 'close' => ''),
                'sunday' => array('open' => '', 'close' => '')
            );
        }
        
        include PD_PLUGIN_PATH . 'templates/admin-studio-details.php';
    }
    
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['pilates_studio_nonce']) || !wp_verify_nonce($_POST['pilates_studio_nonce'], 'pilates_studio_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = array(
            '_studio_address',
            '_studio_phone',
            '_studio_email',
            '_studio_website',
            '_studio_price_range',
            '_studio_latitude',
            '_studio_longitude'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Save opening hours
        if (isset($_POST['_studio_opening_hours'])) {
            $opening_hours = array();
            foreach ($_POST['_studio_opening_hours'] as $day => $hours) {
                $opening_hours[$day] = array(
                    'open' => sanitize_text_field($hours['open']),
                    'close' => sanitize_text_field($hours['close'])
                );
            }
            update_post_meta($post_id, '_studio_opening_hours', $opening_hours);
        }
    }
}