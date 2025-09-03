<?php

function pilates_stockholm_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => 'Huvudmeny',
        'footer' => 'Footermeny',
    ));
    
    // Add image sizes
    add_image_size('studio-card', 350, 200, true);
    add_image_size('studio-hero', 800, 400, true);
}
add_action('after_setup_theme', 'pilates_stockholm_setup');

function pilates_stockholm_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style('pilates-stockholm-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    // Theme stylesheet
    wp_enqueue_style('pilates-stockholm-style', get_stylesheet_uri(), array('pilates-stockholm-fonts'), wp_get_theme()->get('Version'));
    
    // Theme JavaScript
    wp_enqueue_script('pilates-stockholm-script', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), wp_get_theme()->get('Version'), true);
    
    // Localize script for AJAX
    wp_localize_script('pilates-stockholm-script', 'pilates_theme', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('pilates_theme_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'pilates_stockholm_scripts');

function pilates_stockholm_widgets_init() {
    register_sidebar(array(
        'name'          => 'Sidebar',
        'id'            => 'sidebar-1',
        'description'   => 'Huvudsidebar',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => 'Footer',
        'id'            => 'footer-1',
        'description'   => 'Footer widget area',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'pilates_stockholm_widgets_init');

// Custom excerpt length
function pilates_stockholm_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'pilates_stockholm_excerpt_length');

// Custom excerpt more
function pilates_stockholm_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'pilates_stockholm_excerpt_more');

// Add custom body classes
function pilates_stockholm_body_classes($classes) {
    if (is_singular('pilates_studio')) {
        $classes[] = 'single-pilates-studio';
    }
    
    if (is_post_type_archive('pilates_studio')) {
        $classes[] = 'archive-pilates-studio';
    }
    
    return $classes;
}
add_filter('body_class', 'pilates_stockholm_body_classes');

// Enqueue admin styles for Gutenberg editor
function pilates_stockholm_admin_styles() {
    wp_enqueue_style('pilates-stockholm-admin', get_template_directory_uri() . '/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'pilates_stockholm_admin_styles');
add_action('enqueue_block_editor_assets', 'pilates_stockholm_admin_styles');

// Custom login page styling
function pilates_stockholm_login_styles() {
    ?>
    <style>
        body.login {
            background-color: #f5f2ed;
        }
        .login h1 a {
            background-image: none;
            text-indent: 0;
            width: auto;
            height: auto;
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            text-decoration: none;
        }
        .login form {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
        }
        .login .button-primary {
            background-color: #2d5016;
            border-color: #2d5016;
            box-shadow: none;
            text-shadow: none;
            border-radius: 6px;
        }
        .login .button-primary:hover {
            background-color: #8b5a3c;
            border-color: #8b5a3c;
        }
    </style>
    <?php
}
add_action('login_head', 'pilates_stockholm_login_styles');

// Change login logo URL
function pilates_stockholm_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'pilates_stockholm_login_url');

// Change login logo title
function pilates_stockholm_login_title() {
    return get_option('blogname');
}
add_filter('login_headertitle', 'pilates_stockholm_login_title');

// Fallback menu when no menu is assigned
function pilates_stockholm_fallback_menu() {
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Hem</a></li>';
    
    // Studios dropdown with Stockholm areas
    echo '<li class="menu-item-has-children">';
    echo '<a href="' . esc_url(home_url('/studios/')) . '">Studios</a>';
    echo '<ul class="sub-menu">';
    echo '<li><a href="#stockholm-city">Stockholm City</a></li>';
    echo '<li><a href="#sodermalm">Södermalm</a></li>';
    echo '<li><a href="#ostermalm">Östermalm</a></li>';
    echo '<li><a href="#vasastan">Vasastan</a></li>';
    echo '<li><a href="#kungsholmen">Kungsholmen</a></li>';
    echo '<li><a href="#gamla-stan">Gamla Stan</a></li>';
    echo '<li><a href="#norrmalm">Norrmalm</a></li>';
    echo '<li><a href="#djurgarden">Djurgården</a></li>';
    echo '</ul>';
    echo '</li>';
    
    // Pilates types dropdown
    echo '<li class="menu-item-has-children">';
    echo '<a href="' . esc_url(home_url('/pilates-typer/')) . '">Pilates-typer</a>';
    echo '<ul class="sub-menu">';
    echo '<li><a href="#klassisk-pilates">Classical Pilates</a></li>';
    echo '<li><a href="#reformer-pilates">Reformer Pilates</a></li>';
    echo '<li><a href="#contemporary-pilates">Contemporary Pilates</a></li>';
    echo '<li><a href="#flow-pilates">Flow Pilates</a></li>';
    echo '<li><a href="#athletic-pilates">Athletic Pilates</a></li>';
    echo '<li><a href="#mindful-pilates">Mindful Pilates</a></li>';
    echo '<li><a href="#barre-pilates">Barre Pilates</a></li>';
    echo '<li><a href="#yin-pilates">Yin Pilates</a></li>';
    echo '<li><a href="#trx-pilates">TRX Pilates</a></li>';
    echo '<li><a href="#aerial-pilates">Aerial Pilates</a></li>';
    echo '<li><a href="#mat-pilates">Mat Pilates</a></li>';
    echo '<li><a href="#gravidpilates">Gravidpilates</a></li>';
    echo '<li><a href="#rehabilitering">Rehabilitering</a></li>';
    echo '<li><a href="#privattraning">Privatträning</a></li>';
    echo '</ul>';
    echo '</li>';
    
    echo '<li><a href="' . esc_url(home_url('/om/')) . '">Om oss</a></li>';
    echo '<li><a href="' . esc_url(home_url('/kontakt/')) . '">Kontakt</a></li>';
    echo '</ul>';
}