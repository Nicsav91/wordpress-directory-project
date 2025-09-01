<?php

class Pilates_Sample_Data {
    
    public static function create_sample_studios() {
        // Create taxonomy terms first
        self::create_taxonomy_terms();
        
        // Sample studios data
        $studios = array(
            array(
                'title' => 'STHLM Pilates Studio',
                'content' => 'Ett modernt pilatesstudio i hjärtat av Stockholm. Vi erbjuder klassisk pilates på reformer och matwork-klasser för alla nivåer. Våra erfarna instruktörer hjälper dig att uppnå dina hälso- och träningsmal.',
                'address' => 'Kungsgatan 45, Stockholm',
                'phone' => '08-123 456 78',
                'email' => 'info@sthlmpilates.se',
                'website' => 'https://sthlmpilates.se',
                'price_range' => '250-400 kr/klass',
                'latitude' => '59.3363',
                'longitude' => '18.0632',
                'area' => 'Norrmalm',
                'price_class' => 'Medium',
                'specialties' => array('Reformer Pilates', 'Gravidpilates'),
                'image' => 'sthlm-pilates-studio.jpg',
                'opening_hours' => array(
                    'monday' => array('open' => '06:00', 'close' => '21:00'),
                    'tuesday' => array('open' => '06:00', 'close' => '21:00'),
                    'wednesday' => array('open' => '06:00', 'close' => '21:00'),
                    'thursday' => array('open' => '06:00', 'close' => '21:00'),
                    'friday' => array('open' => '06:00', 'close' => '19:00'),
                    'saturday' => array('open' => '08:00', 'close' => '16:00'),
                    'sunday' => array('open' => '09:00', 'close' => '15:00')
                )
            ),
            array(
                'title' => 'Zen Pilates Östermalm',
                'content' => 'Lugnt och harmoniskt pilatesstudio på Östermalm. Vi fokuserar på mindful movement och holistisk träning. Små klasser med personlig uppmärksamhet i en avslappnad miljö.',
                'address' => 'Storgatan 12, Stockholm',
                'phone' => '08-234 567 89',
                'email' => 'hello@zenpilates.se',
                'website' => 'https://zenpilates.se',
                'price_range' => '300-450 kr/klass',
                'latitude' => '59.3345',
                'longitude' => '18.0756',
                'area' => 'Östermalm',
                'price_class' => 'Premium',
                'specialties' => array('Mindful Pilates', 'Yin Pilates'),
                'image' => 'zen-pilates-ostermalm.jpg',
                'opening_hours' => array(
                    'monday' => array('open' => '07:00', 'close' => '20:00'),
                    'tuesday' => array('open' => '07:00', 'close' => '20:00'),
                    'wednesday' => array('open' => '07:00', 'close' => '20:00'),
                    'thursday' => array('open' => '07:00', 'close' => '20:00'),
                    'friday' => array('open' => '07:00', 'close' => '18:00'),
                    'saturday' => array('open' => '08:00', 'close' => '16:00'),
                    'sunday' => array('open' => '', 'close' => '')
                )
            ),
            array(
                'title' => 'Södermalm Pilates Collective',
                'content' => 'Kreativt pilatesstudio på Södermalm som kombinerar traditionell pilates med moderna tekniker. Vi erbjuder allt från nybörjarklasser till avancerad träning.',
                'address' => 'Götgatan 78, Stockholm',
                'phone' => '08-345 678 90',
                'email' => 'studio@sodermalmpilates.com',
                'website' => 'https://sodermalmpilates.com',
                'price_range' => '200-350 kr/klass',
                'latitude' => '59.3139',
                'longitude' => '18.0814',
                'area' => 'Södermalm',
                'price_class' => 'Budget',
                'specialties' => array('Contemporary Pilates', 'Barre Pilates'),
                'image' => 'sodermalm-pilates-collective.jpg',
                'opening_hours' => array(
                    'monday' => array('open' => '06:30', 'close' => '21:30'),
                    'tuesday' => array('open' => '06:30', 'close' => '21:30'),
                    'wednesday' => array('open' => '06:30', 'close' => '21:30'),
                    'thursday' => array('open' => '06:30', 'close' => '21:30'),
                    'friday' => array('open' => '06:30', 'close' => '19:30'),
                    'saturday' => array('open' => '08:00', 'close' => '17:00'),
                    'sunday' => array('open' => '09:00', 'close' => '16:00')
                )
            ),
            array(
                'title' => 'Vasastan Movement Studio',
                'content' => 'Dynamiskt pilatesstudio i Vasastan som specialiserar sig på funktionell rörelse. Perfekt för idrottare och de som vill förbättra sin allmänna kondition och styrka.',
                'address' => 'Upplandsgatan 23, Stockholm',
                'phone' => '08-456 789 01',
                'email' => 'info@vasastanmovement.se',
                'website' => 'https://vasastanmovement.se',
                'price_range' => '280-420 kr/klass',
                'latitude' => '59.3426',
                'longitude' => '18.0537',
                'area' => 'Vasastan',
                'price_class' => 'Medium',
                'specialties' => array('Athletic Pilates', 'Rehabilitering'),
                'image' => 'vasastan-movement-studio.jpg',
                'opening_hours' => array(
                    'monday' => array('open' => '06:00', 'close' => '22:00'),
                    'tuesday' => array('open' => '06:00', 'close' => '22:00'),
                    'wednesday' => array('open' => '06:00', 'close' => '22:00'),
                    'thursday' => array('open' => '06:00', 'close' => '22:00'),
                    'friday' => array('open' => '06:00', 'close' => '20:00'),
                    'saturday' => array('open' => '07:00', 'close' => '18:00'),
                    'sunday' => array('open' => '08:00', 'close' => '17:00')
                )
            ),
            array(
                'title' => 'Pure Pilates Gamla Stan',
                'content' => 'Charmigt pilatesstudio beläget i Gamla Stans historiska miljö. Vi erbjuder klassisk pilates i en unik och inspirerande atmosfär med fokus på teknik och precision.',
                'address' => 'Västerlånggatan 15, Stockholm',
                'phone' => '08-567 890 12',
                'email' => 'contact@purepilates.se',
                'website' => 'https://purepilates.se',
                'price_range' => '320-480 kr/klass',
                'latitude' => '59.3238',
                'longitude' => '18.0687',
                'area' => 'Gamla Stan',
                'price_class' => 'Premium',
                'specialties' => array('Classical Pilates', 'Privatträning'),
                'image' => 'pure-pilates-gamla-stan.jpg',
                'opening_hours' => array(
                    'monday' => array('open' => '07:30', 'close' => '19:30'),
                    'tuesday' => array('open' => '07:30', 'close' => '19:30'),
                    'wednesday' => array('open' => '07:30', 'close' => '19:30'),
                    'thursday' => array('open' => '07:30', 'close' => '19:30'),
                    'friday' => array('open' => '07:30', 'close' => '17:30'),
                    'saturday' => array('open' => '09:00', 'close' => '15:00'),
                    'sunday' => array('open' => '', 'close' => '')
                )
            ),
            array(
                'title' => 'Flow Pilates Kungsholmen',
                'content' => 'Modernt och välutrustat pilatesstudio på Kungsholmen. Vi erbjuder flödesbaserade klasser som kombinerar styrka, flexibilitet och mental fokus för en komplett träningsupplevelse.',
                'address' => 'Scheelegatan 8, Stockholm',
                'phone' => '08-678 901 23',
                'email' => 'info@flowpilates.se',
                'website' => 'https://flowpilates.se',
                'price_range' => '260-380 kr/klass',
                'latitude' => '59.3273',
                'longitude' => '18.0449',
                'area' => 'Kungsholmen',
                'price_class' => 'Medium',
                'specialties' => array('Flow Pilates', 'TRX Pilates'),
                'image' => 'flow-pilates-kungsholmen.jpg',
                'opening_hours' => array(
                    'monday' => array('open' => '06:00', 'close' => '21:00'),
                    'tuesday' => array('open' => '06:00', 'close' => '21:00'),
                    'wednesday' => array('open' => '06:00', 'close' => '21:00'),
                    'thursday' => array('open' => '06:00', 'close' => '21:00'),
                    'friday' => array('open' => '06:00', 'close' => '19:00'),
                    'saturday' => array('open' => '08:00', 'close' => '16:00'),
                    'sunday' => array('open' => '09:00', 'close' => '15:00')
                )
            )
        );
        
        foreach ($studios as $studio_data) {
            self::create_studio($studio_data);
        }
        
        return count($studios);
    }
    
    private static function create_taxonomy_terms() {
        // Areas
        $areas = array('Norrmalm', 'Östermalm', 'Södermalm', 'Vasastan', 'Gamla Stan', 'Kungsholmen');
        foreach ($areas as $area) {
            if (!term_exists($area, 'studio_area')) {
                wp_insert_term($area, 'studio_area');
            }
        }
        
        // Price classes
        $price_classes = array('Budget', 'Medium', 'Premium');
        foreach ($price_classes as $price_class) {
            if (!term_exists($price_class, 'price_class')) {
                wp_insert_term($price_class, 'price_class');
            }
        }
        
        // Specialties
        $specialties = array(
            'Reformer Pilates', 'Gravidpilates', 'Mindful Pilates', 'Yin Pilates',
            'Contemporary Pilates', 'Barre Pilates', 'Athletic Pilates', 'Rehabilitering',
            'Classical Pilates', 'Privatträning', 'Flow Pilates', 'TRX Pilates'
        );
        foreach ($specialties as $specialty) {
            if (!term_exists($specialty, 'specialties')) {
                wp_insert_term($specialty, 'specialties');
            }
        }
    }
    
    private static function create_studio($data) {
        // Check if studio already exists
        $existing = get_page_by_title($data['title'], OBJECT, 'pilates_studio');
        if ($existing) {
            return false;
        }
        
        // Create the post
        $post_id = wp_insert_post(array(
            'post_title' => $data['title'],
            'post_content' => $data['content'],
            'post_status' => 'publish',
            'post_type' => 'pilates_studio',
            'meta_input' => array(
                '_studio_address' => $data['address'],
                '_studio_phone' => $data['phone'],
                '_studio_email' => $data['email'],
                '_studio_website' => $data['website'],
                '_studio_price_range' => $data['price_range'],
                '_studio_latitude' => $data['latitude'],
                '_studio_longitude' => $data['longitude'],
                '_studio_opening_hours' => $data['opening_hours']
            )
        ));
        
        if (is_wp_error($post_id)) {
            return false;
        }
        
        // Set area
        $area_term = get_term_by('name', $data['area'], 'studio_area');
        if ($area_term) {
            wp_set_post_terms($post_id, array($area_term->term_id), 'studio_area');
        }
        
        // Set price class
        $price_term = get_term_by('name', $data['price_class'], 'price_class');
        if ($price_term) {
            wp_set_post_terms($post_id, array($price_term->term_id), 'price_class');
        }
        
        // Set specialties
        $specialty_ids = array();
        foreach ($data['specialties'] as $specialty_name) {
            $specialty_term = get_term_by('name', $specialty_name, 'specialties');
            if ($specialty_term) {
                $specialty_ids[] = $specialty_term->term_id;
            }
        }
        if (!empty($specialty_ids)) {
            wp_set_post_terms($post_id, $specialty_ids, 'specialties');
        }
        
        // Set featured image if image exists
        if (isset($data['image'])) {
            $image_path = WP_CONTENT_DIR . '/uploads/studios/' . $data['image'];
            if (file_exists($image_path)) {
                $image_url = content_url('uploads/studios/' . $data['image']);
                $attachment_id = self::create_attachment_from_url($image_url, $post_id, $data['title']);
                if ($attachment_id) {
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }
        }
        
        return $post_id;
    }
    
    private static function create_attachment_from_url($image_url, $parent_post_id, $title) {
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_url);
        
        if ($image_data === false) {
            return false;
        }
        
        $filename = basename($image_url);
        $file = $upload_dir['path'] . '/' . $filename;
        
        // Save the image data to file
        if (file_put_contents($file, $image_data) === false) {
            return false;
        }
        
        // Create attachment
        $attachment = array(
            'guid' => $upload_dir['url'] . '/' . $filename,
            'post_mime_type' => wp_check_filetype($filename)['type'],
            'post_title' => $title,
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        $attachment_id = wp_insert_attachment($attachment, $file, $parent_post_id);
        
        if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
            return $attachment_id;
        }
        
        return false;
    }
    
    public static function remove_sample_studios() {
        $studios = get_posts(array(
            'post_type' => 'pilates_studio',
            'posts_per_page' => -1,
            'post_status' => 'any'
        ));
        
        foreach ($studios as $studio) {
            wp_delete_post($studio->ID, true);
        }
        
        return count($studios);
    }
}

// Add admin menu for sample data
add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=pilates_studio',
        'Sample Data',
        'Sample Data',
        'manage_options',
        'pilates-sample-data',
        function() {
            if (isset($_POST['create_sample_data']) && wp_verify_nonce($_POST['nonce'], 'pilates_sample_data')) {
                $count = Pilates_Sample_Data::create_sample_studios();
                echo '<div class="notice notice-success"><p>' . $count . ' sample studios created!</p></div>';
            }
            
            if (isset($_POST['remove_sample_data']) && wp_verify_nonce($_POST['nonce'], 'pilates_sample_data')) {
                $count = Pilates_Sample_Data::remove_sample_studios();
                echo '<div class="notice notice-success"><p>' . $count . ' studios removed!</p></div>';
            }
            
            ?>
            <div class="wrap">
                <h1>Pilates Directory Sample Data</h1>
                
                <div class="card">
                    <h2>Create Sample Studios</h2>
                    <p>This will create 6 sample pilates studios with realistic data for testing.</p>
                    <form method="post">
                        <?php wp_nonce_field('pilates_sample_data', 'nonce'); ?>
                        <button type="submit" name="create_sample_data" class="button button-primary">Create Sample Data</button>
                    </form>
                </div>
                
                <div class="card" style="margin-top: 20px;">
                    <h2>Remove All Studios</h2>
                    <p><strong>Warning:</strong> This will permanently delete all pilates studios.</p>
                    <form method="post">
                        <?php wp_nonce_field('pilates_sample_data', 'nonce'); ?>
                        <button type="submit" name="remove_sample_data" class="button button-secondary" 
                                onclick="return confirm('Are you sure you want to delete all studios?')">Remove All Studios</button>
                    </form>
                </div>
            </div>
            <?php
        }
    );
});