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
<span id="pagename" data-name="Realty"></span>
<div class="c-hero c-hero--img c-hero--with-box">
    <div class="c-hero__brand">
        <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-box-white.svg'; ?>"
             alt="IGEL Logo weiß">
    </div>
    <picture class="c-hero__bg" data-only="mobile" data-action="open-gallery"
             data-r="<?php echo $realty->getId(); ?>">
        <img alt="<?php echo get_the_title(); ?> Thumbnail"
             src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id()); ?>"
             srcset="<?php echo wp_get_attachment_image_srcset(get_post_thumbnail_id()); ?>"/>
    </picture>
    <div class="c-gallery__icon-toggle -only-mobile" data-action="open-gallery"
         data-r="<?php echo $realty->getId(); ?>">
        Galerie (<?php echo count($realty->getAttachments()); ?>)
        <i class="ig ig-image"></i>
    </div>
    <?php
    if (isset($realty->getCategories()[21829])) {
        echo '<span class="overlay-image -left"><img src="' . get_stylesheet_directory_uri() . '/assets/img/klimaaktiv.png' . '" alt="Klimaaktiv Logo"></span>';
    }
    ?>
    <div class="c-hero__overlay"></div>
</div>

<div class="c-hero__box-wrap">
    <div class="c-hero__box col">
        <div class="c-highlights">
            <?php

            $isRent    = !$realty->getMarketingType()['KAUF'];
            $price     = ig_price(!$isRent ? $realty->getPurchasePrice() : $realty->getTotalRent());
            $priceType = $isRent ? 'Mietpreis ' : 'Kaufpreis';
            $price     = empty($price) ? 'Auf Anfrage' : "$price";


            $data    = [
                [
                    'icon'        => 'area',
                    'value'       => number_format($realty->getLivingArea(), 0, ',', '.'),
                    'valueSuffix' => 'm<sup>2</sup>',
                    'name'        => 'Wohnfläche',
                ],
                [
                    'icon'        => 'area-2',
                    'value'       => number_format($realty->getSurfaceArea() ?? $realty->getTotalArea(), 0, ',', '.'),
                    'valueSuffix' => 'm<sup>2</sup>',
                    'name'        => $realty->getFloorArea() ? 'Grundfläche' : 'Grundfläche',
                ],
                [
                    'icon'  => 'house',
                    'value' => $realty->getRoomCount(),
                    'name'  => 'Zimmer',
                ],
                [
                    'icon'  => 'bill',
                    'value' => $price,
                    'name'  => $priceType,
                ],
                [
                    'icon'  => 'time',
                    'value' => $realty->getYearBuilt(),
                    'name'  => 'Baujahr',
                ],
                [
                    'icon'  => 'car',
                    'value' => $realty->getGarageCount(),
                    'name'  => 'Garagen',
                ],
                [
                    'icon'  => 'area-3',
                    'value' => $realty->getBalconyCount(),
                    'name'  => 'Balkons',
                ],
                [
                    'icon'  => 'houses',
                    'value' => ucfirst(strtolower($realty->getAge())),
                    'name'  => 'Gebäudetyp',
                ],
                [
                    'icon'  => 'time',
                    'value' => ucfirst(strtolower($realty->getYearBuilt())),
                    'name'  => 'Baujahr',
                ],
                [
                    'icon'  => 'time',
                    'value' => ucfirst(strtolower($realty->getCondition())),
                    'name'  => 'Zustand',
                ],
            ];
            $showing = 0;
            foreach ($data as $row):
                if ($showing === 4) {
                    break;
                }
                if (empty($row['value'])) {
                    continue;
                }
                $showing++;
                ?>
                <div class="c-highlights__el">
                    <div class="text-highlight text-main">
                        <i class="ig ig-<?php echo $row['icon']; ?>"></i>
                        <?php echo $row['value'] . ($row['valueSuffix'] ?? ''); ?>
                    </div>
                    <div class="c-highlights__el__desc">
                        <?php echo $row['name']; ?>
                    </div>
                </div>
            <?php
            endforeach;
            ?>
        </div>
        <div class="c-gallery__button button -only-desktop-flex" data-action="open-gallery"
             data-r="<?php echo $realty->getId(); ?>">
            <i class="ig ig-media"></i>
            Bilder (<?php echo count($realty->getAttachments()); ?>)
        </div>
        <div class="c-hero__box__overlay-wrap">
            <div class="c-hero__box__overlay"></div>
        </div>
    </div>
</div>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <div class="content">

            <section>

                <?php igTitle($realty->getTitle(), $realty->getZipCode() . ' ' . $realty->getPlace(), 'h1'); ?>

                <?php
                echo empty(get_the_content())
                    ? 'Leider liegt zu diesem Objekt aktuell keine Beschreibung. Wenn Sie Interesse an weiteren ' .
                      'Informationen haben werden wir Ihnen diese gerne auf eine persönliche Anfrage zukommen lassen.'
                    : get_the_content();
                ?>
                <br/>

                <?php
                if (!empty($realty->getVideos())) {
                    foreach ($realty->getVideos() as $video) {
                        /** @var Justimmo\Model\Attachment $video */
                        echo '<video src="' . $video->getUrl() . '" style="width:100%;max-width:640px; margin-bottom:15px;" controls></video>';
                    }
                }
                ?>

                <br/>

                <?php echo do_shortcode('[share]'); ?>

            </section>

            <?php echo do_shortcode('[divider]'); ?>

            <?php
            /** @var Justimmo\Model\Employee $contact */
            $contact = $realty->getContact();
            $user    = igel()->realtyPosts()->getWpUser($contact);

            echo '<section class="content">';
            igTitle('Ihr Ansprechpartner', 'Sie haben Interesse?', 'div');
            render_agents([$user]);
            render_mini_contact_form($user);
            echo '</section>';
            ?>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>


