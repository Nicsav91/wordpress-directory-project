<?php

class Pilates_Shortcodes {
    
    public function __construct() {
        add_shortcode('pilates_directory', array($this, 'directory_shortcode'));
        add_shortcode('pilates_map', array($this, 'map_shortcode'));
        add_shortcode('pilates_search', array($this, 'search_shortcode'));
    }
    
    public function directory_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 12,
            'area' => '',
            'price_class' => '',
            'specialty' => ''
        ), $atts);
        
        $args = array(
            'post_type' => 'pilates_studio',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        );
        
        // Add taxonomy filters
        $tax_query = array();
        
        if (!empty($atts['area'])) {
            $tax_query[] = array(
                'taxonomy' => 'studio_area',
                'field' => 'slug',
                'terms' => $atts['area']
            );
        }
        
        if (!empty($atts['price_class'])) {
            $tax_query[] = array(
                'taxonomy' => 'price_class',
                'field' => 'slug',
                'terms' => $atts['price_class']
            );
        }
        
        if (!empty($atts['specialty'])) {
            $tax_query[] = array(
                'taxonomy' => 'specialties',
                'field' => 'slug',
                'terms' => $atts['specialty']
            );
        }
        
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }
        
        $studios = new WP_Query($args);
        
        ob_start();
        ?>
        <style>
        .studio-card .studio-actions .btn {
            background-color: #2d5016 !important;
            color: white !important;
            border: 1px solid #2d5016 !important;
        }
        .studio-card .studio-info {
            background-color: #8b5a3c !important;
            min-height: 400px !important;
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .studio-card {
            background-color: #8b5a3c !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .studio-image {
            background-color: #8b5a3c !important;
        }
        .studio-actions {
            margin-top: auto !important;
        }
        .specialty-tag {
            background-color: #1a1a1a !important;
            color: white !important;
        }
        .studio-excerpt {
            font-size: 1.1rem !important;
        }
        .studio-card-rating .rating-stars {
            color: white !important;
        }
        .studio-name a {
            font-weight: bold !important;
        }
        </style>
        <div class="pilates-directory">
            <?php echo $this->render_filters(); ?>
            
            <div class="studios-grid" id="studios-grid">
                <?php if ($studios->have_posts()): ?>
                    <?php while ($studios->have_posts()): $studios->the_post(); ?>
                        <?php echo $this->render_studio_card(get_the_ID()); ?>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else: ?>
                    <p>Inga studios hittades.</p>
                <?php endif; ?>
            </div>
            
            <div class="load-more-container">
                <button id="load-more-studios" class="btn btn-secondary">Visa fler</button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function render_filters() {
        $areas = get_terms(array(
            'taxonomy' => 'studio_area',
            'hide_empty' => true
        ));
        
        $price_classes = get_terms(array(
            'taxonomy' => 'price_class',  
            'hide_empty' => true
        ));
        
        $specialties = get_terms(array(
            'taxonomy' => 'specialties',
            'hide_empty' => true
        ));
        
        ob_start();
        ?>
        <div class="pilates-filters">
            <h3>Filtrera studios</h3>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="area-filter">Område:</label>
                    <select id="area-filter" name="area">
                        <option value="">Alla områden</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo esc_attr(is_object($area) ? $area->slug : $area['slug']); ?>">
                                <?php echo esc_html(is_object($area) ? $area->name : $area['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="price-filter">Prisklass:</label>
                    <select id="price-filter" name="price_class">
                        <option value="">Alla prisklasser</option>
                        <?php foreach ($price_classes as $price_class): ?>
                            <option value="<?php echo esc_attr(is_object($price_class) ? $price_class->slug : $price_class['slug']); ?>">
                                <?php echo esc_html(is_object($price_class) ? $price_class->name : $price_class['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="specialty-filter">Specialitet:</label>
                    <select id="specialty-filter" name="specialty">
                        <option value="">Alla specialiteter</option>
                        <?php foreach ($specialties as $specialty): ?>
                            <option value="<?php echo esc_attr(is_object($specialty) ? $specialty->slug : $specialty['slug']); ?>">
                                <?php echo esc_html(is_object($specialty) ? $specialty->name : $specialty['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search-input">Sök:</label>
                    <input type="text" id="search-input" placeholder="Sök efter studionamn...">
                </div>
                
                <div class="filter-group">
                    <button type="button" id="apply-filters" class="btn btn-primary">Filtrera</button>
                    <button type="button" id="clear-filters" class="btn btn-secondary">Rensa</button>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function render_studio_card($studio_id) {
        $address = get_post_meta($studio_id, '_studio_address', true);
        $phone = get_post_meta($studio_id, '_studio_phone', true);
        $price_range = get_post_meta($studio_id, '_studio_price_range', true);
        
        // Get taxonomies
        $areas = wp_get_post_terms($studio_id, 'studio_area');
        $specialties = wp_get_post_terms($studio_id, 'specialties');
        
        // Get reviews
        $review_system = new Pilates_Review();
        $rating_data = $review_system->get_studio_average_rating($studio_id);
        
        ob_start();
        ?>
        <div class="studio-card">
            <div class="studio-image">
                <?php 
                $studio_image = get_post_meta($studio_id, '_studio_image', true);
                if ($studio_image && file_exists(WP_CONTENT_DIR . '/uploads/studios/' . $studio_image)): 
                ?>
                    <img src="<?php echo content_url('uploads/studios/' . $studio_image); ?>" alt="<?php echo esc_attr(get_the_title($studio_id)); ?>" class="studio-thumb">
                <?php elseif (has_post_thumbnail($studio_id)): ?>
                    <?php echo get_the_post_thumbnail($studio_id, 'medium', array('class' => 'studio-thumb')); ?>
                <?php else: ?>
                    <div class="placeholder-image">Ingen bild</div>
                <?php endif; ?>
                
                <?php if ($rating_data['count'] > 0): ?>
                    <div class="rating-badge">
                        <span class="rating-stars"><?php echo $review_system->display_stars($rating_data['average']); ?></span>
                        <span class="rating-count">(<?php echo $rating_data['count']; ?>)</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="studio-info">
                <h3 class="studio-name">
                    <a href="<?php echo get_permalink($studio_id); ?>">
                        <?php echo get_the_title($studio_id); ?>
                    </a>
                </h3>
                
                <div class="studio-card-rating">
                    <?php if ($rating_data['count'] > 0): ?>
                        <span class="rating-stars">
                            <?php 
                            $rating = round($rating_data['average']);
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $rating ? '★' : '☆';
                            }
                            ?>
                        </span>
                        <span class="rating-text"><?php echo number_format($rating_data['average'], 1); ?> av 5 stjärnor</span>
                    <?php else: ?>
                        <?php
                        // Set specific ratings for certain studios
                        $studio_title = get_the_title($studio_id);
                        if (strpos($studio_title, 'Vasastan') !== false || strpos($studio_title, 'STHLM') !== false) {
                            $random_rating = 4;
                        } else {
                            // Create a consistent random rating based on studio ID
                            $seed = $studio_id % 1000; // Use studio ID as seed for consistency
                            srand($seed);
                            $random_rating = rand(3, 5);
                            srand(); // Reset random seed
                        }
                        ?>
                        <span class="rating-stars">
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $random_rating ? '★' : '☆';
                            }
                            ?>
                        </span>
                        <span class="rating-text"><?php echo $random_rating; ?> av 5 stjärnor</span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($areas)): ?>
                    <div class="studio-area">
                        <span class="area-label black-emoji">📍</span>
                        <?php echo esc_html($areas[0]->name); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($address): ?>
                    <div class="studio-address">
                        <?php echo esc_html($address); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($phone): ?>
                    <div class="studio-phone">
                        <span class="black-emoji">📞</span> <?php echo esc_html($phone); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($price_range): ?>
                    <div class="studio-price">
                        <span class="black-emoji">💰</span> <?php echo esc_html($price_range); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($specialties)): ?>
                    <div class="studio-specialties">
                        <?php foreach ($specialties as $specialty): ?>
                            <span class="specialty-tag"><?php echo esc_html($specialty->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="studio-excerpt">
                    <?php echo get_the_excerpt($studio_id); ?>
                </div>
                
                <div class="studio-actions">
                    <a href="<?php echo get_permalink($studio_id); ?>" class="btn btn-success">
                        Läs mer
                    </a>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function search_shortcode($atts) {
        ob_start();
        ?>
        <div class="pilates-search-widget">
            <form class="search-form" id="pilates-search-form">
                <input type="text" name="search" id="pilates-search" placeholder="Sök pilates studio...">
                <button type="submit">Sök</button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function map_shortcode($atts) {
        $atts = shortcode_atts(array(
            'height' => '400px',
            'zoom' => 12
        ), $atts);
        
        ob_start();
        ?>
        <div class="pilates-map-container">
            <div id="pilates-map" style="height: <?php echo esc_attr($atts['height']); ?>;"></div>
        </div>
        
        <script>
        // Define global callback function for Google Maps
        window.initPilatesMap = function() {
            if (document.getElementById('pilates-map')) {
                const map = new google.maps.Map(document.getElementById('pilates-map'), {
                    zoom: <?php echo intval($atts['zoom']); ?>,
                    center: {lat: 59.3293, lng: 18.0686}, // Stockholm center
                    styles: [
                        {
                            featureType: 'poi',
                            elementType: 'labels',
                            stylers: [{visibility: 'off'}]
                        }
                    ]
                });
                
                // Load studio markers via AJAX
                loadStudioMarkers(map);
            }
        };
        
        function loadStudioMarkers(map) {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=get_studio_markers')
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        data.forEach(studio => {
                            const marker = new google.maps.Marker({
                                position: {lat: parseFloat(studio.lat), lng: parseFloat(studio.lng)},
                                map: map,
                                title: studio.name
                            });
                            
                            const infoWindow = new google.maps.InfoWindow({
                                content: `
                                    <div class="map-studio-info">
                                        <h4><a href="${studio.url}">${studio.name}</a></h4>
                                        <p>${studio.address}</p>
                                        ${studio.rating ? `<div class="rating">${studio.rating}/5 ⭐</div>` : ''}
                                    </div>
                                `
                            });
                            
                            marker.addListener('click', () => {
                                infoWindow.open(map, marker);
                            });
                        });
                    }
                })
                .catch(error => {
                    console.log('Could not load studio markers:', error);
                });
        }
        
        // Fallback initialization if callback doesn't work
        if (typeof google !== 'undefined' && google.maps) {
            initPilatesMap();
        }
        </script>
        <?php
        return ob_get_clean();
    }
}