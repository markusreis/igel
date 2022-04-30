<?php
/**
 * Template Name: Makler-Seite
 * @package igel
 */

if (have_posts()) the_post();

get_header();

$data = get_field('about_page_settings');
?>
    <div class="c-hero c-hero--green">
        <div class="c-hero__brand">
            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-box-white.svg'; ?>"
                 alt="IGEL Logo weiß">
        </div>
        <div class="content">
            <?php igTitle('404', 'Wir konnten diese Seite leider nicht finden...', 'h1'); ?>
        </div>
    </div>


    <div id="primary" class="content-area">
        <main id="main" class="site-main content">

            Die gesucht Seite existiert leider nicht...

            <a href="<?php echo get_home_url(); ?>" style="display:block;margin:40px 0 0 0;font-weight:bold;">Zurück zur
                Startseite</a>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
