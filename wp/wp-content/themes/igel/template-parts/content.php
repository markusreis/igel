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
        <?php
        the_content();
        ?>
    </section><!-- .entry-content -->

</article>

