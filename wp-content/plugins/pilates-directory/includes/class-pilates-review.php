<?php

class Pilates_Review {
    
    public function __construct() {
        add_action('wp_footer', array($this, 'add_review_modal'));
        add_action('wp_ajax_add_pilates_review', array($this, 'handle_add_review'));
        add_action('wp_ajax_nopriv_add_pilates_review', array($this, 'handle_add_review'));
    }
    
    public function get_studio_reviews($studio_id, $limit = 5) {
        global $wpdb;
        
        $reviews_table = $wpdb->prefix . 'pilates_reviews';
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT r.*, u.display_name 
             FROM $reviews_table r 
             LEFT JOIN {$wpdb->users} u ON r.user_id = u.ID 
             WHERE r.studio_id = %d 
             ORDER BY r.created_at DESC 
             LIMIT %d",
            $studio_id,
            $limit
        ));
        
        return $results;
    }
    
    public function get_studio_average_rating($studio_id) {
        global $wpdb;
        
        $reviews_table = $wpdb->prefix . 'pilates_reviews';
        
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT AVG(rating) as average_rating, COUNT(*) as review_count 
             FROM $reviews_table 
             WHERE studio_id = %d",
            $studio_id
        ));
        
        return array(
            'average' => round($result->average_rating, 1),
            'count' => $result->review_count
        );
    }
    
    public function display_reviews($studio_id) {
        $reviews = $this->get_studio_reviews($studio_id);
        $rating_data = $this->get_studio_average_rating($studio_id);
        
        ob_start();
        ?>
        <div class="pilates-reviews">
            <div class="reviews-header">
                <h3>Recensioner</h3>
                <div class="average-rating">
                    <?php if ($rating_data['count'] > 0): ?>
                        <div class="stars">
                            <?php echo $this->display_stars($rating_data['average']); ?>
                        </div>
                        <span class="rating-text">
                            <?php echo $rating_data['average']; ?>/5 
                            (<?php echo $rating_data['count']; ?> recensioner)
                        </span>
                    <?php else: ?>
                        <span class="no-reviews">Inga recensioner än</span>
                    <?php endif; ?>
                </div>
                
                <?php if (is_user_logged_in()): ?>
                    <button class="btn btn-primary" onclick="openReviewModal(<?php echo $studio_id; ?>)">
                        Skriv recension
                    </button>
                <?php else: ?>
                    <p><a href="<?php echo wp_login_url(get_permalink()); ?>">Logga in</a> för att skriva recension</p>
                <?php endif; ?>
            </div>
            
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <strong><?php echo esc_html($review->display_name ?: 'Anonym'); ?></strong>
                            <div class="review-rating">
                                <?php echo $this->display_stars($review->rating); ?>
                            </div>
                            <span class="review-date">
                                <?php echo date('j M Y', strtotime($review->created_at)); ?>
                            </span>
                        </div>
                        <div class="review-content">
                            <?php echo esc_html($review->comment); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function display_stars($rating) {
        $stars = '';
        $full_stars = floor($rating);
        $half_star = ($rating - $full_stars) >= 0.5;
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $full_stars) {
                $stars .= '<span class="star full">★</span>';
            } elseif ($i == $full_stars + 1 && $half_star) {
                $stars .= '<span class="star half">☆</span>';
            } else {
                $stars .= '<span class="star empty">☆</span>';
            }
        }
        
        return $stars;
    }
    
    public function add_review_modal() {
        if (!is_single() || get_post_type() != 'pilates_studio') {
            return;
        }
        ?>
        <div id="reviewModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Skriv en recension</h3>
                <form id="reviewForm">
                    <div class="rating-input">
                        <label>Betyg:</label>
                        <div class="star-rating">
                            <input type="radio" name="rating" value="5" id="star5">
                            <label for="star5">★</label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4">★</label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3">★</label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2">★</label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1">★</label>
                        </div>
                    </div>
                    <div class="comment-input">
                        <label for="review-comment">Kommentar:</label>
                        <textarea id="review-comment" name="comment" rows="4" required></textarea>
                    </div>
                    <input type="hidden" name="studio_id" id="review-studio-id">
                    <button type="submit">Skicka recension</button>
                </form>
            </div>
        </div>
        <?php
    }
    
    public function handle_add_review() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'pilates_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error('Du måste vara inloggad för att skriva recensioner');
            return;
        }
        
        $studio_id = intval($_POST['studio_id']);
        $rating = intval($_POST['rating']);
        $comment = sanitize_textarea_field($_POST['comment']);
        $user_id = get_current_user_id();
        
        // Validate input
        if ($rating < 1 || $rating > 5) {
            wp_send_json_error('Ogiltigt betyg');
            return;
        }
        
        if (empty($comment)) {
            wp_send_json_error('Kommentar krävs');
            return;
        }
        
        global $wpdb;
        $reviews_table = $wpdb->prefix . 'pilates_reviews';
        
        // Check if user already reviewed this studio
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $reviews_table WHERE studio_id = %d AND user_id = %d",
            $studio_id,
            $user_id
        ));
        
        if ($existing) {
            wp_send_json_error('Du har redan recenserat denna studio');
            return;
        }
        
        // Insert review
        $result = $wpdb->insert(
            $reviews_table,
            array(
                'studio_id' => $studio_id,
                'user_id' => $user_id,
                'rating' => $rating,
                'comment' => $comment,
                'created_at' => current_time('mysql')
            ),
            array('%d', '%d', '%d', '%s', '%s')
        );
        
        if ($result) {
            wp_send_json_success('Recension tillagd!');
        } else {
            wp_send_json_error('Något gick fel, försök igen');
        }
    }
}