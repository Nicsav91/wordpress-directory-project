<?php get_header(); ?>

<div class="archive-header">
    <div class="container">
        <h1>Alla Pilatesstudios</h1>
        <p>Bläddra genom alla våra pilatesstudios i Stockholm</p>
    </div>
</div>

<div class="directory-section">
    <div class="container">
        <?php echo do_shortcode('[pilates_directory limit="20"]'); ?>
    </div>
</div>

<?php get_footer(); ?>