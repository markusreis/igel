<?php
/**
 * Template Name: Fragebogen-Seite
 * @package igel
 */

if (have_posts()) the_post();

get_header();

$type = get_field('type');

hero(
    $type === 'evaluationRequest' ? 'Sie wollen verkaufen?' : 'Sie suchen eine Immobilie?',
    $type === 'evaluationRequest' ? 'Wir bewerten Ihre Immobilie. Sofort und kostenlos.' : 'Wir helfen Ihnen das passende Objekt zu finden',
    false
);

?>

    <span id="pagename" data-name="Page"></span>


    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            echo '<section class="content">';

            echo '<div class="c-evaluation__search-wrap">';
            echo do_shortcode('[evaluation_form config="' . $type . '"]');
            echo '</div>';

            if (!empty(get_the_content())) {
                echo do_shortcode('[divider]');
                the_content();
            }

            echo '</section>';
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
