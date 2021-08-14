<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ThemeReplace
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <section class="content">
        <div style="height:400px"></div>
        <?php
        $r = (\Igel\Services\RealtyPostService::getInstance())->getRealty();

        echo acf_get_attachment(get_post_thumbnail_id())['sizes']['medium'];
        the_content();
        ?>
    </section><!-- .entry-content -->

    <footer class="entry-footer">
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php
the_ID(); ?> -->
