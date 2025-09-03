    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-sections">
                <!-- About Section -->
                <div class="footer-section">
                    <h3>Om oss</h3>
                    <p>Vi hjälper dig att hitta den perfekta pilatesstudion i Stockholm. Vårt mål är att göra det enkelt att upptäcka och jämföra studios baserat på dina behov och önskemål.</p>
                    <p>Alla studios är noggrant utvalda och kvalitetsgranskade för att säkerställa den bästa upplevelsen.</p>
                </div>
                
                <!-- Contact Section -->
                <div class="footer-section">
                    <h3>Kontakt</h3>
                    <div class="contact-info">
                        <p>📧 info@pilatesstockholm.se</p>
                        <p>📞 08-123 456 78</p>
                        <p>📍 Stockholm, Sverige</p>
                    </div>
                    <div class="social-links">
                        <a href="#" target="_blank" rel="noopener">Facebook</a>
                        <a href="#" target="_blank" rel="noopener">Instagram</a>
                        <a href="#" target="_blank" rel="noopener">Twitter</a>
                    </div>
                </div>
                
                <!-- Add Studio Section -->
                <div class="footer-section">
                    <h3>För studieägare</h3>
                    <p>Äger du en pilatesstudio i Stockholm? Vi hjälper dig att nå fler kunder!</p>
                    <a href="#" class="btn btn-primary footer-cta">Lägg till din studio</a>
                    <div class="studio-benefits">
                        <p>✓ Ökad synlighet</p>
                        <p>✓ Kvalificerade kunder</p>
                        <p>✓ Enkelt att använda</p>
                    </div>
                </div>
                
                <!-- Newsletter Section -->
                <div class="footer-section">
                    <h3>Håll dig uppdaterad</h3>
                    <p>Få de senaste nyheterna om pilates, nya studios och erbjudanden direkt i din inkorg.</p>
                    <form class="newsletter-form" action="#" method="post">
                        <div class="newsletter-input-group">
                            <input type="email" name="newsletter_email" placeholder="Din e-postadress" required>
                            <button type="submit" class="btn btn-primary">Prenumerera</button>
                        </div>
                        <p class="newsletter-disclaimer">Vi respekterar din integritet och skickar endast relevant information.</p>
                    </form>
                </div>
            </div>
            
            <?php if (is_active_sidebar('footer-1')): ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>
            
            <div class="site-info">
                <div class="footer-bottom">
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