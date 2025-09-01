<?php
$studio_id = get_the_ID();
$address = get_post_meta($studio_id, '_studio_address', true);
$phone = get_post_meta($studio_id, '_studio_phone', true);
$email = get_post_meta($studio_id, '_studio_email', true);
$website = get_post_meta($studio_id, '_studio_website', true);
$price_range = get_post_meta($studio_id, '_studio_price_range', true);
$latitude = get_post_meta($studio_id, '_studio_latitude', true);
$longitude = get_post_meta($studio_id, '_studio_longitude', true);
$opening_hours = get_post_meta($studio_id, '_studio_opening_hours', true);

// Get taxonomies
$areas = wp_get_post_terms($studio_id, 'studio_area');
$price_classes = wp_get_post_terms($studio_id, 'price_class');
$specialties = wp_get_post_terms($studio_id, 'specialties');

// Get reviews
$review_system = new Pilates_Review();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="studio-header">
        <div class="container">
            <div class="studio-header-content">
                <h1 class="studio-title"><?php the_title(); ?></h1>
                
                <?php if (!empty($areas)): ?>
                    <div class="studio-location">
                        <span class="location-icon">📍</span>
                        <?php echo esc_html($areas[0]->name); ?>, Stockholm
                    </div>
                <?php endif; ?>
                
                <?php 
                $rating_data = $review_system->get_studio_average_rating($studio_id);
                if ($rating_data['count'] > 0): 
                ?>
                    <div class="studio-rating">
                        <?php echo $review_system->display_stars($rating_data['average']); ?>
                        <span class="rating-text">
                            <?php echo $rating_data['average']; ?>/5 
                            (<?php echo $rating_data['count']; ?> recensioner)
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="studio-details">
            <div class="studio-main-info">
                <?php if (has_post_thumbnail()): ?>
                    <div class="studio-featured-image">
                        <?php the_post_thumbnail('studio-hero'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="studio-content">
                    <?php the_content(); ?>
                </div>
                
                <?php if (!empty($specialties)): ?>
                    <div class="studio-specialties-section">
                        <h3>Specialiteter</h3>
                        <div class="specialties-list">
                            <?php foreach ($specialties as $specialty): ?>
                                <span class="specialty-tag"><?php echo esc_html($specialty->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($latitude && $longitude): ?>
                    <div class="studio-map-section">
                        <h3>Karta</h3>
                        <div id="single-studio-map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
                        <script>
                        function initSingleStudioMap() {
                            const map = new google.maps.Map(document.getElementById('single-studio-map'), {
                                zoom: 15,
                                center: {lat: <?php echo floatval($latitude); ?>, lng: <?php echo floatval($longitude); ?>}
                            });
                            
                            new google.maps.Marker({
                                position: {lat: <?php echo floatval($latitude); ?>, lng: <?php echo floatval($longitude); ?>},
                                map: map,
                                title: '<?php echo esc_js(get_the_title()); ?>'
                            });
                        }
                        
                        if (typeof google !== 'undefined') {
                            google.maps.event.addDomListener(window, 'load', initSingleStudioMap);
                        }
                        </script>
                    </div>
                <?php endif; ?>
                
                <?php echo $review_system->display_reviews($studio_id); ?>
            </div>
            
            <aside class="studio-meta">
                <?php if ($address): ?>
                    <div class="meta-item">
                        <div class="meta-label">Adress</div>
                        <div class="meta-value"><?php echo esc_html($address); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($phone): ?>
                    <div class="meta-item">
                        <div class="meta-label">Telefon</div>
                        <div class="meta-value">
                            <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($email): ?>
                    <div class="meta-item">
                        <div class="meta-label">E-post</div>
                        <div class="meta-value">
                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($website): ?>
                    <div class="meta-item">
                        <div class="meta-label">Webbsida</div>
                        <div class="meta-value">
                            <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener">
                                Besök webbsida
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($price_range): ?>
                    <div class="meta-item">
                        <div class="meta-label">Prisintervall</div>
                        <div class="meta-value"><?php echo esc_html($price_range); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($price_classes)): ?>
                    <div class="meta-item">
                        <div class="meta-label">Prisklass</div>
                        <div class="meta-value"><?php echo esc_html($price_classes[0]->name); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($opening_hours && is_array($opening_hours)): ?>
                    <div class="meta-item">
                        <div class="meta-label">Öppettider</div>
                        <div class="meta-value">
                            <table class="opening-hours-table">
                                <?php
                                $days_swedish = array(
                                    'monday' => 'Måndag',
                                    'tuesday' => 'Tisdag',
                                    'wednesday' => 'Onsdag',
                                    'thursday' => 'Torsdag',
                                    'friday' => 'Fredag',
                                    'saturday' => 'Lördag',
                                    'sunday' => 'Söndag'
                                );
                                
                                foreach ($days_swedish as $day => $swedish_day):
                                    $open = $opening_hours[$day]['open'] ?? '';
                                    $close = $opening_hours[$day]['close'] ?? '';
                                ?>
                                    <tr>
                                        <th><?php echo $swedish_day; ?></th>
                                        <td>
                                            <?php 
                                            if (empty($open) && empty($close)) {
                                                echo 'Stängt';
                                            } else {
                                                echo esc_html($open . ' - ' . $close);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="meta-item">
                    <a href="<?php echo esc_url(home_url('/pilates-studios')); ?>" class="btn btn-secondary">
                        ← Tillbaka till alla studios
                    </a>
                </div>
            </aside>
        </div>
    </div>
</article>