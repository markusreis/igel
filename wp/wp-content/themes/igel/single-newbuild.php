<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ThemeReplace
 */

use Igel\Services\RealtyPostService;

$realty = RealtyPostService::getInstance()->getRealty();

get_header();
?>

<span id="pagename" data-name="NewBuild"></span>
<div class="c-hero c-hero--img c-hero--overflow">

    <?php
    if (get_field('hide_wdf') !== true):
        ?>
        <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/wdf-schleife.png'; ?>"
             alt="Wohn dich frei Schleife" class="c-new-build-highlight__badge">
    <?php
    endif;
    ?>

    <?php
    $gallery = get_field('gallery');
    if (!empty($gallery)):
        ?>
        <div class="c-gallery__icon-toggle" data-action="open-gallery" data-p="<?php echo get_the_ID(); ?>">
            Galerie (<?php echo count($gallery); ?>)
            <i class="ig ig-image"></i>
        </div>
        <?php
        $id = $gallery[0]['ID'];
        ?>
        <picture class="c-hero__bg" data-action="open-gallery" data-only="mobile" data-p="<?php echo get_the_ID(); ?>">
            <img alt="<?php echo get_the_title(); ?> Thumbnail"
                 src="<?php echo wp_get_attachment_image_url($id) ?>"
                 srcset="<?php echo wp_get_attachment_image_srcset($id) ?>"/>
        </picture>

        <?php
        $klimaAktiv  = get_field('show_klimaaktiv');
        $logoOverlay = get_field('logo_overlay');
        if ($klimaAktiv) {
            echo '<span class="overlay-image -left"><img src="' . get_stylesheet_directory_uri() . '/assets/img/klimaaktiv.png' . '" alt="Klimaaktiv Logo"></span>';
        }
        if (!empty($logoOverlay)) {
            echo '<img class="overlay-image -right" src="' . $logoOverlay . '" alt="Neubauprojekt Logo">';
        }
        ?>
    <?php
    endif;
    ?>
    <div class="c-hero__overlay"></div>
</div>

<?php
$tabs = get_field('tabs');
if (!empty($tabs)):
    ?>
    <div class="c-hero__box-wrap c-hero__box-wrap--no-padding">
        <div class="c-hero__box">
            <div class="c-tabs">
                <div class="c-tabs__tab c-tabs__tab--active" data-js-target="overview">
                    <span class="c-tabs__marker"></span>
                    <span class="c-tabs__title">
                        Übersicht
                    </span>
                </div>
                <?php
                foreach ($tabs as $tab) :
                    echo '<div class="c-tabs__tab" data-js-target="' . acf_slugify($tab['name']) . '"><span class="c-tabs__title">' . $tab['name'] . '</span></div>';
                endforeach;
                ?>
            </div>
            <div class="c-hero__box__overlay-wrap">
                <div class="c-hero__box__overlay"></div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="c-tabs__scrollbar">
            <span class="c-tabs__scrollbar__thumb"></span>
        </div>
    </div>
<?php
endif;
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main -mt-0@lg">

        <section class="content" style="<?php echo empty($tabs) ? 'margin-top:45px' : ''; ?>">
            <?php
            igTitle(null, get_field('post_code') . ' ' . get_field('place'), 'h1');
            ?>

            <div class="c-tabs__content">
                <div class="c-tabs__content__inner c-tabs__content__inner--active" data-slug="overview">
                    <?php
                    the_content();
                    ?>

                    <br/>
                    <?php echo do_shortcode('[share]'); ?>
                </div>

                <?php
                if (!empty($tabs)):
                    foreach ($tabs as $tab) {
                        ?>
                        <div class="c-tabs__content__inner c-tabs__content__inner--passive"
                             data-slug="<?php echo acf_slugify($tab['name']); ?>">
                            <?php
                            switch ($tab['acf_fc_layout']) {

                                case 'free':
                                    echo $tab['text'];
                                    break;

                                case 'list':
                                    echo '<ul class="c-tabs__list">';
                                    foreach ($tab['rows'] as $row) {
                                        if (empty($row['picture']) && empty($row['youtube_link'])) {
                                            echo '<li class="c-tabs__list__el "> ' . (isset($row['pretitle']) && !empty($row['pretitle']) ? '<span style="display:block;color:#7b8679;margin-bottom:5px;">' . $row['pretitle'] . '</span>' : '') . '
                                               ' . (!empty($row['title']) ? '<strong style="display:block; margin-bottom:15px;font-size:18px;">' . $row['title'] . '</strong>' : '') . $row['text'] . '</li>';
                                        } else {
                                            echo '<li class="c-tabs__list__el cols row@lg">
                                            <div class="col-7@lg">
                                            ' . (isset($row['pretitle']) && !empty($row['pretitle']) ? '<span style="display:block;color:#7b8679;margin-bottom:5px;">' . $row['pretitle'] . '</span>' : '') . '
                                               ' . (!empty($row['title']) ? '<strong style="display:block; margin-bottom:15px;font-size:18px;">' . $row['title'] . '</strong>' : '') . $row['text'] . '
                                            </div>
                                            <div class="col-1@xl"></div>
                                            <div class="col-5@lg col-4@xl">
                                                ' . (!empty($row['youtube_link']) ? '<iframe style="width:100%;height: 100%;min-width:100px;min-height: 100px;" src="' . $row['youtube_link'] . '" frameborder="0"></iframe>' : '')
                                                 . (empty($row['youtube_link']) ? '<picture>
                                                    <img alt="Immobilien Neubau Bild"
                                                         src="' . wp_get_attachment_image_url($row['picture']['ID']) . '"
                                                         srcset="' . wp_get_attachment_image_srcset($row['picture']['ID']) . '"/>
                                                </picture>' : '')
                                                 . '</div>
                                            </li>';
                                        }
                                    }
                                    echo '</ul>';
                                    break;

                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                <?php
                endif;
                ?>
            </div>
        </section>


        <?php
        $users = get_field('contact_persons');

        if (!empty($users)):

            ?>
            <div class="content">
                <?php echo do_shortcode('[divider]'); ?>
            </div>
            <?php

            if (count($users) === -1) {
                $user = array_shift($users);
                render_ansprechpartner($user['ansprechpartner'], $user['provision']);
            } else {
                echo '<section class="content">';
                igTitle(count($users) === 1 ? 'Ihr Ansprechpartner' : 'Ihre Ansprechpartner', 'Sie haben Interesse?', 'div');
                $users = array_map(function ($user) {
                    return get_user_by('id', $user['ansprechpartner']['ID']);
                }, $users);
                render_agents($users);
                render_mini_contact_form(array_shift($users));
                echo '</section>';
            }
        endif;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>


