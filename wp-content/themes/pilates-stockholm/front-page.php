<?php get_header(); ?>

<?php 
$hero_image_path = get_template_directory() . '/../../uploads/hero/pilates-hero.jpg';
$hero_class = file_exists($hero_image_path) ? 'hero-section with-background' : 'hero-section';
?>
<div class="<?php echo $hero_class; ?>">
    <div class="container">
        <div class="hero-content">
            <h1>Hitta ditt perfekta pilatesstudio i Stockholm</h1>
            <p class="hero-subtitle">Upptäck de bästa pilatesstudiosen i Stockholm. Sök, jämför och hitta det som passar dig.</p>
            
            <div class="hero-search">
                <?php echo do_shortcode('[pilates_search]'); ?>
            </div>
        </div>
    </div>
</div>

<div class="directory-section">
    <div class="container">
        <div class="section-header">
            <h2>Pilatesstudios i Stockholm</h2>
            <p>Bläddra genom vårt urval av kvalitetsgranskade pilatesstudios</p>
        </div>
        
        <?php echo do_shortcode('[pilates_directory limit="12"]'); ?>
    </div>
</div>

<div class="map-section">
    <div class="container">
        <h2>Hitta studios nära dig</h2>
        <?php echo do_shortcode('[pilates_map height="500px"]'); ?>
    </div>
</div>

<div class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature">
                <div class="feature-icon">🔍</div>
                <h3>Enkel sökning</h3>
                <p>Sök efter studios baserat på område, prisklass och specialiteter</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">⭐</div>
                <h3>Recensioner</h3>
                <p>Läs recensioner från andra användare och få hjälp att välja rätt</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">📍</div>
                <h3>Kartvvy</h3>
                <p>Se alla studios på kartan och hitta det som ligger närmast dig</p>
            </div>
            
            <div class="feature">
                <div class="feature-icon">💳</div>
                <h3>Prisinfo</h3>
                <p>Jämför priser och hitta studios som passar din budget</p>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>