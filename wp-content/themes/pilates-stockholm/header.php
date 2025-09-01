<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php if (has_custom_logo()): ?>
                    <?php the_custom_logo(); ?>
                <?php else: ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php 
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()): ?>
                        <p class="site-description"><?php echo $description; ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-text">Meny</span>
                    <span class="menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container_class' => 'primary-menu-container',
                    'fallback_cb' => 'pilates_stockholm_fallback_menu'
                ));
                ?>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content">