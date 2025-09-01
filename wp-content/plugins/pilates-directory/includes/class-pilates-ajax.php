<?php

class Pilates_Ajax {
    
    public function __construct() {
        // AJAX actions for logged-in and non-logged-in users
        add_action('wp_ajax_filter_studios', array($this, 'filter_studios'));
        add_action('wp_ajax_nopriv_filter_studios', array($this, 'filter_studios'));
        
        add_action('wp_ajax_load_more_studios', array($this, 'load_more_studios'));
        add_action('wp_ajax_nopriv_load_more_studios', array($this, 'load_more_studios'));
        
        add_action('wp_ajax_get_studio_markers', array($this, 'get_studio_markers'));
        add_action('wp_ajax_nopriv_get_studio_markers', array($this, 'get_studio_markers'));
        
        add_action('wp_ajax_search_studios', array($this, 'search_studios'));
        add_action('wp_ajax_nopriv_search_studios', array($this, 'search_studios'));
    }
    
    public function filter_studios() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'pilates_nonce')) {
            wp_die('Security check failed');
        }
        
        $area = sanitize_text_field($_POST['area']);
        $price_class = sanitize_text_field($_POST['price_class']);
        $specialty = sanitize_text_field($_POST['specialty']);
        $search = sanitize_text_field($_POST['search']);
        $page = intval($_POST['page']) ?: 1;
        
        $args = array(
            'post_type' => 'pilates_studio',
            'posts_per_page' => 12,
            'paged' => $page,
            'post_status' => 'publish'
        );
        
        // Add search query
        if (!empty($search)) {
            $args['s'] = $search;
        }
        
        // Build taxonomy query
        $tax_query = array();
        
        if (!empty($area)) {
            $tax_query[] = array(
                'taxonomy' => 'studio_area',
                'field' => 'slug',
                'terms' => $area
            );
        }
        
        if (!empty($price_class)) {
            $tax_query[] = array(
                'taxonomy' => 'price_class',
                'field' => 'slug',
                'terms' => $price_class
            );
        }
        
        if (!empty($specialty)) {
            $tax_query[] = array(
                'taxonomy' => 'specialties',
                'field' => 'slug',
                'terms' => $specialty
            );
        }
        
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }
        
        $studios = new WP_Query($args);
        
        $response = array(
            'studios' => array(),
            'has_more' => false
        );
        
        if ($studios->have_posts()) {
            $shortcodes = new Pilates_Shortcodes();
            
            while ($studios->have_posts()) {
                $studios->the_post();
                $response['studios'][] = $shortcodes->render_studio_card(get_the_ID());
            }
            
            $response['has_more'] = $studios->max_num_pages > $page;
            wp_reset_postdata();
        }
        
        wp_send_json_success($response);
    }
    
    public function load_more_studios() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'pilates_nonce')) {
            wp_die('Security check failed');
        }
        
        $page = intval($_POST['page']) ?: 1;
        
        $args = array(
            'post_type' => 'pilates_studio',
            'posts_per_page' => 12,
            'paged' => $page,
            'post_status' => 'publish'
        );
        
        $studios = new WP_Query($args);
        
        $response = array(
            'studios' => array(),
            'has_more' => false
        );
        
        if ($studios->have_posts()) {
            $shortcodes = new Pilates_Shortcodes();
            
            while ($studios->have_posts()) {
                $studios->the_post();
                $response['studios'][] = $shortcodes->render_studio_card(get_the_ID());
            }
            
            $response['has_more'] = $studios->max_num_pages > $page;
            wp_reset_postdata();
        }
        
        wp_send_json_success($response);
    }
    
    public function get_studio_markers() {
        $args = array(
            'post_type' => 'pilates_studio',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_studio_latitude',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => '_studio_longitude', 
                    'compare' => 'EXISTS'
                )
            )
        );
        
        $studios = new WP_Query($args);
        $markers = array();
        
        if ($studios->have_posts()) {
            $review_system = new Pilates_Review();
            
            while ($studios->have_posts()) {
                $studios->the_post();
                $studio_id = get_the_ID();
                
                $lat = get_post_meta($studio_id, '_studio_latitude', true);
                $lng = get_post_meta($studio_id, '_studio_longitude', true);
                $address = get_post_meta($studio_id, '_studio_address', true);
                
                if (!empty($lat) && !empty($lng)) {
                    $rating_data = $review_system->get_studio_average_rating($studio_id);
                    
                    $markers[] = array(
                        'lat' => floatval($lat),
                        'lng' => floatval($lng),
                        'name' => get_the_title(),
                        'address' => $address,
                        'url' => get_permalink(),
                        'rating' => $rating_data['count'] > 0 ? $rating_data['average'] : null
                    );
                }
            }
            wp_reset_postdata();
        }
        
        wp_send_json($markers);
    }
    
    public function search_studios() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'pilates_nonce')) {
            wp_die('Security check failed');
        }
        
        $search_term = sanitize_text_field($_POST['search']);
        
        if (empty($search_term)) {
            wp_send_json_error('Sökterm krävs');
            return;
        }
        
        $args = array(
            'post_type' => 'pilates_studio',
            'posts_per_page' => 10,
            'post_status' => 'publish',
            's' => $search_term
        );
        
        // Also search in custom fields and taxonomies
        add_filter('posts_search', array($this, 'extend_search'), 10, 2);
        
        $studios = new WP_Query($args);
        
        remove_filter('posts_search', array($this, 'extend_search'), 10, 2);
        
        $results = array();
        
        if ($studios->have_posts()) {
            while ($studios->have_posts()) {
                $studios->the_post();
                $studio_id = get_the_ID();
                
                $results[] = array(
                    'id' => $studio_id,
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'address' => get_post_meta($studio_id, '_studio_address', true),
                    'excerpt' => get_the_excerpt()
                );
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success($results);
    }
    
    public function extend_search($search, $wp_query) {
        global $wpdb;
        
        if (empty($search)) {
            return $search;
        }
        
        $search_term = $wp_query->query_vars['s'];
        
        if (empty($search_term)) {
            return $search;
        }
        
        // Add search in meta fields
        $meta_search = "OR EXISTS (
            SELECT * FROM {$wpdb->postmeta} 
            WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID 
            AND (
                ({$wpdb->postmeta}.meta_key = '_studio_address' AND {$wpdb->postmeta}.meta_value LIKE '%{$search_term}%')
                OR ({$wpdb->postmeta}.meta_key = '_studio_phone' AND {$wpdb->postmeta}.meta_value LIKE '%{$search_term}%')
            )
        )";
        
        // Add search in taxonomy terms
        $tax_search = "OR EXISTS (
            SELECT * FROM {$wpdb->term_relationships} tr
            LEFT JOIN {$wpdb->term_taxonomy} tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
            LEFT JOIN {$wpdb->terms} t ON (tt.term_id = t.term_id)
            WHERE tr.object_id = {$wpdb->posts}.ID 
            AND tt.taxonomy IN ('studio_area', 'price_class', 'specialties')
            AND t.name LIKE '%{$search_term}%'
        )";
        
        $search = preg_replace('/\)\s*$/', ') ' . $meta_search . ' ' . $tax_search . ')', $search);
        
        return $search;
    }
}