    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if (is_active_sidebar('footer-1')): ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>
            
            <div class="site-info">
                <div class="footer-content">
                    <div class="footer-text">
                        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Alla rättigheter förbehålls.</p>
                        <p>Hitta de bästa pilatesstudiosen i Stockholm</p>
                    </div>
                    
                    <div class="footer-menu">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class' => 'footer-menu-list',
                            'depth' => 1,
                            'fallback_cb' => false
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>