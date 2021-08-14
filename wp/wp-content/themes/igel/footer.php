<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ThemeReplace
 */
?>

</div><!-- #content -->

<!-- footer -->
<footer id="colophon" class="site-footer layout__row--full">

    <?php
    $data = get_field('footer_settings', 'options');
    if (!empty($data)):
        ?>

        <img class="site-footer__brand-bg"
             src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-box-white.svg'; ?>"
             alt="IGEL Logo Weiß">
        <img class="site-footer__brand-bg"
             src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-box-white.svg'; ?>"
             alt="IGEL Logo Weiß">
        <img class="site-footer__brand-bg"
             src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-box-white.svg'; ?>"
             alt="IGEL Logo Weiß">

        <div class="content row">
            <div class="col-12 col-6@md col-3@xl site-footer__brand-col">
                <img class="site-footer__brand"
                     src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-white.svg'; ?>"
                     alt="IGEL Immobilien Logo Weiß">

                <div class="site-footer__copyright">
                    <?php echo $data['copyright_text']; ?>
                </div>

                <div class="site-footer__social-links">
                    <?php echo array_reduce($data['social_media_links'], function ($all, $el) {
                        return $all .= '<a href="' . $el['link'] . '" target="_blank" rel="nofollow" class="ig ig-' . $el['icon'] . '"><span class="u-sr-only">' . ucfirst($el['icon']) . '</span></a>';
                    }, ''); ?>
                </div>
            </div>
            <div class="col-12 col-6@md col-3@xl site-footer__company-col">
                <div class="site-footer__title">Unternehmen</div>
                <?php
                wp_nav_menu(
                    array(
                        'container'       => 'div',
                        'container_class' => false,
                        'menu_class'      => false,
                        'menu_id'         => false,
                        'theme_location'  => 'footer_company',
                    )
                );
                ?>
            </div>
            <div class="col-12 col-6@md col-3@xl site-footer__legal-col">
                <div class="site-footer__title">Rechtliches</div>
                <?php
                wp_nav_menu(
                    array(
                        'container'       => 'div',
                        'container_class' => false,
                        'menu_class'      => false,
                        'menu_id'         => false,
                        'theme_location'  => 'footer_legal',
                    )
                );
                ?>
            </div>
            <div class="col-12 col-6@md col-3@xl site-footer__contact-col">
                <div class="site-footer__title site-footer__title--big">IGEL Immobilien</div>
                <div class="site-footer__contact">
                    <?php echo $data['contact']; ?>
                </div>
            </div>
        </div>

    <?php
    endif;
    ?>

</footer><!-- #footer -->
<?php wp_footer(); ?>

</body>
</html>
