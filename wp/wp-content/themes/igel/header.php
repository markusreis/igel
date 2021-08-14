<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ThemeReplace
 */


?>
<!doctype html>
<html <?php language_attributes(); ?><?php echo is_front_page() ? ' data-page="Homepage"' : ''; ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php
    $faviconBasePath = get_stylesheet_directory_uri() . '/assets/favicon';
    ?>
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $faviconBasePath; ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $faviconBasePath; ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $faviconBasePath; ?>/favicon-16x16.png">
    <link rel="manifest" href="<?php echo $faviconBasePath; ?>/site.webmanifest">
    <link rel="mask-icon" href="<?php echo $faviconBasePath; ?>/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <title>IGEL - Work in progress</title>
    <?php
    wp_head();
    ?>
</head>

<body <?php body_class(); ?> data-barba="wrapper">

<div id="preload" style="position: fixed;width: 100vw;height:100vh;top: 0;left: 0;background:white;z-index:999;"></div>

<header class="header">

    <div class="content header__content">
        <a href="<?php echo get_home_url(); ?>" class="header__brand">
            <div class="header__brand__inner">
                <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand.svg'; ?>"
                     alt="IGEL Immobilien Logo">
            </div>
        </a>

        <input type="checkbox" id="toggle-nav" name="toggle-nav" class="nav__toggle">
        <label for="toggle-nav" class="nav__toggle__bars">
            <span class="u-sr-only">Navigation ausklappen</span>
            <span class="nav__toggle__bars__bg">
                <span class="nav__toggle__bar nav__toggle__bar--top"></span>
                <span class="nav__toggle__bar nav__toggle__bar--bottom"></span>
            </span>
        </label>

        <nav class="nav">
            <?php
            wp_nav_menu(
                array(
                    'container_class' => 'nav__container',
                    'menu_class'      => false,
                    'menu_id'         => false,
                    'theme_location'  => 'main',
                )
            );
            ?>
            <a class="nav__contact-link" href="#"><i class="ig ig-phone"></i></a>
        </nav>
    </div>

</header><!-- #masthead -->

<div id="content" data-barba="container" data-barba-namespace="main">
