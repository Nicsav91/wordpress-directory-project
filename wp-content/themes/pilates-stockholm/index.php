<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <h1 class="site-title">
                    <a href="<?php echo home_url(); ?>">
                        Pilates Stockholm
                    </a>
                </h1>
                
                <nav class="main-navigation">
                    <?php 
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'fallback_cb' => false
                    )); 
                    ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="site-main">
        <?php if (is_home() || is_front_page()): ?>
            <section class="hero-section">
                <div class="container">
                    <h1 class="hero-title">Hitta din perfekta pilates-studio</h1>
                    <p class="hero-subtitle">Upptäck de bästa pilates-studiorna i Stockholm</p>
                    <?php echo do_shortcode('[pilates_search]'); ?>
                </div>
            </section>
            
            <div class="container">
                <?php echo do_shortcode('[pilates_directory]'); ?>
            </div>
        <?php else: ?>
            <div class="container">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        if (is_singular('pilates_studio')) {
                            get_template_part('template-parts/content', 'pilates-studio');
                        } else {
                            get_template_part('template-parts/content', get_post_type());
                        }
                    endwhile;
                else :
                    get_template_part('template-parts/content', 'none');
                endif;
                ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> Pilates Stockholm. Alla rättigheter förbehållna.</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>