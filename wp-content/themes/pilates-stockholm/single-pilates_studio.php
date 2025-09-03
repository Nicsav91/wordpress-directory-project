<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-studio'); ?>>
        
        <div class="studio-header">
            <div class="container">
                <div class="studio-header-content">
                    <div class="studio-main-info">
                        <h1 class="studio-title"><?php the_title(); ?></h1>
                        
                        <?php 
                        $areas = wp_get_post_terms(get_the_ID(), 'studio_area');
                        if (!empty($areas)): ?>
                            <div class="studio-location">
                                <span class="location-icon">📍</span>
                                <?php echo esc_html($areas[0]->name); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        $review_system = new Pilates_Review();
                        $rating_data = $review_system->get_studio_average_rating(get_the_ID());
                        if ($rating_data['count'] > 0): ?>
                            <div class="studio-rating">
                                <span class="rating-stars"><?php echo $review_system->display_stars($rating_data['average']); ?></span>
                                <span class="rating-text"><?php echo number_format($rating_data['average'], 1); ?>/5</span>
                                <span class="rating-count">(<?php echo $rating_data['count']; ?> recensioner)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php 
                    $studio_image = get_post_meta(get_the_ID(), '_studio_image', true);
                    if ($studio_image && file_exists(WP_CONTENT_DIR . '/uploads/studios/' . $studio_image)): 
                    ?>
                        <div class="studio-featured-image">
                            <img src="<?php echo content_url('uploads/studios/' . $studio_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    <?php elseif (has_post_thumbnail()): ?>
                        <div class="studio-featured-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="studio-content">
            <div class="container">
                <div class="studio-details">
                    <div class="studio-main-content">
                        <div class="studio-description">
                            <?php the_content(); ?>
                        </div>
                        
                        <?php 
                        $specialties = wp_get_post_terms(get_the_ID(), 'specialties');
                        if (!empty($specialties)): ?>
                            <div class="studio-specialties-section">
                                <h3>Specialiteter</h3>
                                <div class="specialties-list">
                                    <?php foreach ($specialties as $specialty): ?>
                                        <span class="specialty-tag"><?php echo esc_html($specialty->name); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        // Display reviews
                        if (class_exists('Pilates_Review')):
                            $review_system = new Pilates_Review();
                            echo $review_system->display_reviews_section(get_the_ID());
                        endif;
                        ?>
                    </div>
                    
                    <div class="studio-sidebar">
                        <div class="studio-meta">
                            <?php 
                            $address = get_post_meta(get_the_ID(), '_studio_address', true);
                            $phone = get_post_meta(get_the_ID(), '_studio_phone', true);
                            $email = get_post_meta(get_the_ID(), '_studio_email', true);
                            $website = get_post_meta(get_the_ID(), '_studio_website', true);
                            $price_range = get_post_meta(get_the_ID(), '_studio_price_range', true);
                            ?>
                            
                            <?php if ($address): ?>
                                <div class="meta-item">
                                    <div class="meta-label">📍 Adress</div>
                                    <div class="meta-value"><?php echo esc_html($address); ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($phone): ?>
                                <div class="meta-item">
                                    <div class="meta-label">📞 Telefon</div>
                                    <div class="meta-value">
                                        <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($email): ?>
                                <div class="meta-item">
                                    <div class="meta-label">✉️ E-post</div>
                                    <div class="meta-value">
                                        <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($website): ?>
                                <div class="meta-item">
                                    <div class="meta-label">🌐 Webbsida</div>
                                    <div class="meta-value">
                                        <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener">Besök webbsida</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($price_range): ?>
                                <div class="meta-item">
                                    <div class="meta-label">💰 Priser</div>
                                    <div class="meta-value"><?php echo esc_html($price_range); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php 
                        $opening_hours = get_post_meta(get_the_ID(), '_studio_opening_hours', true);
                        if ($opening_hours): ?>
                            <div class="studio-opening-hours">
                                <h3>Öppettider</h3>
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
                                    
                                    foreach ($days_swedish as $day => $day_name):
                                        $open = isset($opening_hours[$day]['open']) ? $opening_hours[$day]['open'] : '';
                                        $close = isset($opening_hours[$day]['close']) ? $opening_hours[$day]['close'] : '';
                                    ?>
                                        <tr>
                                            <td><?php echo $day_name; ?></td>
                                            <td>
                                                <?php if (empty($open) || empty($close)): ?>
                                                    <span class="closed">Stängt</span>
                                                <?php else: ?>
                                                    <?php echo esc_html($open) . ' - ' . esc_html($close); ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        $latitude = get_post_meta(get_the_ID(), '_studio_latitude', true);
                        $longitude = get_post_meta(get_the_ID(), '_studio_longitude', true);
                        if ($latitude && $longitude): ?>
                            <div class="studio-map">
                                <h3>Hitta hit</h3>
                                <div id="single-studio-map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
                            </div>
                            
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
                                
                                // Initialize map when Google Maps is loaded
                                if (typeof google !== 'undefined' && google.maps) {
                                    initSingleStudioMap();
                                } else {
                                    window.addEventListener('load', function() {
                                        if (typeof google !== 'undefined' && google.maps) {
                                            initSingleStudioMap();
                                        }
                                    });
                                }
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>