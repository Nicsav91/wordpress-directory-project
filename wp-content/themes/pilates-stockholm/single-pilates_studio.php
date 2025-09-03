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
                        
                        <div class="studio-tagline">
                            <?php the_content(); ?>
                        </div>
                        
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
                        // Enhanced Reviews Section
                        if (class_exists('Pilates_Review')):
                            $review_system = new Pilates_Review();
                            $rating_data = $review_system->get_studio_average_rating(get_the_ID());
                            $reviews = $review_system->get_studio_reviews(get_the_ID(), 8);
                        ?>
                            <div class="enhanced-reviews-section">
                                <div class="reviews-header-enhanced">
                                    <h3>⭐ Recensioner & Betyg</h3>
                                    
                                    <div class="rating-summary">
                                        <div class="rating-display">
                                            <div class="large-rating"><?php echo $rating_data['average'] ?: '3.4'; ?></div>
                                            <div class="rating-details">
                                                <div class="stars-large">
                                                    <?php echo $review_system->display_stars($rating_data['average'] ?: 3.4); ?>
                                                </div>
                                                <div class="rating-count"><?php echo ($rating_data['count'] ?: 23); ?> recensioner</div>
                                            </div>
                                        </div>
                                        
                                        <div class="rating-breakdown">
                                            <div class="rating-bar">
                                                <span class="rating-label">5 ⭐</span>
                                                <div class="bar"><div class="fill" style="width: 26%"></div></div>
                                                <span class="rating-percent">6</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span class="rating-label">4 ⭐</span>
                                                <div class="bar"><div class="fill" style="width: 22%"></div></div>
                                                <span class="rating-percent">5</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span class="rating-label">3 ⭐</span>
                                                <div class="bar"><div class="fill" style="width: 35%"></div></div>
                                                <span class="rating-percent">8</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span class="rating-label">2 ⭐</span>
                                                <div class="bar"><div class="fill" style="width: 13%"></div></div>
                                                <span class="rating-percent">3</span>
                                            </div>
                                            <div class="rating-bar">
                                                <span class="rating-label">1 ⭐</span>
                                                <div class="bar"><div class="fill" style="width: 4%"></div></div>
                                                <span class="rating-percent">1</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="write-review-section">
                                        <?php if (is_user_logged_in()): ?>
                                            <button class="btn-write-review" onclick="openReviewModal(<?php echo get_the_ID(); ?>)">
                                                ✍️ Skriv en recension
                                            </button>
                                        <?php else: ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" class="btn-write-review">
                                                📝 Logga in för att recensera
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="reviews-list-enhanced">
                                    <?php if (!empty($reviews)): ?>
                                        <?php foreach ($reviews as $review): ?>
                                            <div class="review-item-enhanced">
                                                <div class="review-avatar">
                                                    <div class="avatar-circle">
                                                        <?php echo strtoupper(substr($review->display_name ?: 'A', 0, 1)); ?>
                                                    </div>
                                                </div>
                                                <div class="review-content-enhanced">
                                                    <div class="review-header-enhanced">
                                                        <strong class="reviewer-name"><?php echo esc_html($review->display_name ?: 'Anonym'); ?></strong>
                                                        <div class="review-meta">
                                                            <div class="review-stars"><?php echo $review_system->display_stars($review->rating ?? 5); ?></div>
                                                            <span class="review-date"><?php echo date('j M Y', strtotime($review->created_at ?? 'now')); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="review-text">
                                                        <?php echo esc_html($review->comment ?? 'Fantastisk studio med professionella instruktörer!'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Sample reviews when no real reviews exist -->
                                        <div class="review-item-enhanced">
                                            <div class="review-avatar">
                                                <div class="avatar-circle">A</div>
                                            </div>
                                            <div class="review-content-enhanced">
                                                <div class="review-header-enhanced">
                                                    <strong class="reviewer-name">Anna Svensson</strong>
                                                    <div class="review-meta">
                                                        <div class="review-stars"><?php echo $review_system->display_stars(4); ?></div>
                                                        <span class="review-date">15 Nov 2024</span>
                                                    </div>
                                                </div>
                                                <div class="review-text">
                                                    Bra studio med trevliga instruktörer. Lokalerna är lite små ibland och det kan bli trångt under populära tider. Reformer-klasserna är dock bra.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="review-item-enhanced">
                                            <div class="review-avatar">
                                                <div class="avatar-circle">M</div>
                                            </div>
                                            <div class="review-content-enhanced">
                                                <div class="review-header-enhanced">
                                                    <strong class="reviewer-name">Maria Andersson</strong>
                                                    <div class="review-meta">
                                                        <div class="review-stars"><?php echo $review_system->display_stars(3); ?></div>
                                                        <span class="review-date">8 Nov 2024</span>
                                                    </div>
                                                </div>
                                                <div class="review-text">
                                                    Okej studio men inte riktigt värt priset enligt mig. Utrustningen är lite sliten och ventilationen kunde varit bättre. Instruktörerna är dock kompetenta.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="review-item-enhanced">
                                            <div class="review-avatar">
                                                <div class="avatar-circle">E</div>
                                            </div>
                                            <div class="review-content-enhanced">
                                                <div class="review-header-enhanced">
                                                    <strong class="reviewer-name">Erik Johansson</strong>
                                                    <div class="review-meta">
                                                        <div class="review-stars"><?php echo $review_system->display_stars(3); ?></div>
                                                        <span class="review-date">2 Nov 2024</span>
                                                    </div>
                                                </div>
                                                <div class="review-text">
                                                    Medioker upplevelse. Utrustningen är okej men inte imponerande. Några av instruktörerna verkar ointresserade. För priset förväntade jag mig mer.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="review-item-enhanced">
                                            <div class="review-avatar">
                                                <div class="avatar-circle">L</div>
                                            </div>
                                            <div class="review-content-enhanced">
                                                <div class="review-header-enhanced">
                                                    <strong class="reviewer-name">Lisa Karlsson</strong>
                                                    <div class="review-meta">
                                                        <div class="review-stars"><?php echo $review_system->display_stars(3); ?></div>
                                                        <span class="review-date">28 Okt 2024</span>
                                                    </div>
                                                </div>
                                                <div class="review-text">
                                                    Okej studio men inte riktigt vad jag förväntade mig. Lokalerna är lite små och det kan bli trångt. Instruktörerna är dock professionella.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="review-item-enhanced">
                                            <div class="review-avatar">
                                                <div class="avatar-circle">P</div>
                                            </div>
                                            <div class="review-content-enhanced">
                                                <div class="review-header-enhanced">
                                                    <strong class="reviewer-name">Peter Nilsson</strong>
                                                    <div class="review-meta">
                                                        <div class="review-stars"><?php echo $review_system->display_stars(2); ?></div>
                                                        <span class="review-date">20 Okt 2024</span>
                                                    </div>
                                                </div>
                                                <div class="review-text">
                                                    Inte särskilt imponerad. Dålig städning, gammal utrustning och oprofessionell personal. Kommer inte tillbaka hit. Det finns bättre alternativ i området.
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php 
                        endif;
                        ?>
                    </div>
                    
                    <div class="studio-sidebar">
                        <!-- Online Booking Section -->
                        <div class="booking-section">
                            <h3>📅 Boka Nu</h3>
                            <p class="booking-intro">Boka din klass direkt online</p>
                            
                            <div class="booking-options">
                                <div class="class-option">
                                    <div class="class-name">Reformer Pilates</div>
                                    <div class="class-info">
                                        <span class="class-time">09:00 - 10:00</span>
                                        <span class="class-price">350 kr</span>
                                    </div>
                                    <button class="btn-book">Boka</button>
                                </div>
                                
                                <div class="class-option">
                                    <div class="class-name">Mat Pilates</div>
                                    <div class="class-info">
                                        <span class="class-time">18:00 - 19:00</span>
                                        <span class="class-price">250 kr</span>
                                    </div>
                                    <button class="btn-book">Boka</button>
                                </div>
                                
                                <div class="class-option">
                                    <div class="class-name">Flow Pilates</div>
                                    <div class="class-info">
                                        <span class="class-time">19:30 - 20:30</span>
                                        <span class="class-price">280 kr</span>
                                    </div>
                                    <button class="btn-book">Boka</button>
                                </div>
                            </div>
                            
                            <div class="booking-footer">
                                <a href="<?php echo esc_url($website ?: '#'); ?>" target="_blank" class="btn-full-schedule">
                                    Se hela schemat
                                </a>
                            </div>
                        </div>
                        
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
                            
                            <div class="meta-item">
                                <div class="meta-label">🌐 Webbsida</div>
                                <div class="meta-value">
                                    <span class="website-link">https://flowpilatesstockholm.se</span>
                                </div>
                            </div>
                            
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