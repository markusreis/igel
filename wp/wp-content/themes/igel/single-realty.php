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
    <picture class="c-hero__bg">
        <img alt="<?php echo get_the_title(); ?> Thumbnail"
             src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id()); ?>"
             srcset="<?php echo wp_get_attachment_image_srcset(get_post_thumbnail_id()); ?>"/>
    </picture>
    <div class="c-hero__overlay"></div>
</div>

<div class="c-hero__box-wrap">
    <div class="c-hero__box">
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
                    'value'       => number_format($realty->getFloorArea() ?? $realty->getTotalArea(), 0, ',', '.'),
                    'valueSuffix' => 'm<sup>2</sup>',
                    'name'        => $realty->getFloorArea() ? 'Grundfläche' : 'Gesamtfläche',
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
        <div class="c-gallery__button button" data-action="open-gallery" data-r="<?php echo $realty->getId(); ?>">
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
            </section>

            <?php echo do_shortcode('[divider]'); ?>

            <?php
            /** @var Justimmo\Model\Employee $contact */
            $contact = $realty->getContact();
            ?>

            <section class="content cols-reverse row@lg c-two-cols c-two-cols--agents">
                <div class="col-12 col-5@lg">
                    <div class="picture--cover picture--h-full">
                        <?php
                        $user = igel()->realtyPosts()->getWpUser($contact);
                        $img  = get_field('portrait', 'user_' . $user->ID);
                        if (!empty($img)) {
                            ?>
                            <img alt="<?php echo $contact->getFirstName() . ' ' . $contact->getLastName(); ?> Portrait"
                                 src="<?php echo wp_get_attachment_image_url($img['ID']); ?>"
                                 srcset="<?php echo wp_get_attachment_image_srcset($img['ID']); ?>"/>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12 col-1@lg"></div>
                <div class="col-12 col-6@lg">
                    <?php igTitle($contact->getFirstName() . ' ' . $contact->getLastName(), 'Ihr Ansprechpartner', 'div'); ?>

                    <?php
                    if (!empty($contact->getPhone())) :
                        ?>
                        <div class="c-icon-text">
                            <i class="c-icon-text__icon ig ig-phone"></i>
                            <a class="c-icon-text__value" href="tel:<?php echo $contact->getPhone(); ?>">
                                <?php echo $contact->getPhone(); ?>
                            </a>
                        </div>
                    <?php
                    endif;
                    ?>

                    <div class="c-icon-text">
                        <i class="c-icon-text__icon ig ig-mail"></i>
                        <a class="c-icon-text__value" href="mailto:<?php echo $contact->getEmail(); ?>">
                            <?php echo $contact->getEmail(); ?>
                        </a>
                    </div>

                    <div class="c-icon-text">
                        <i class="c-icon-text__icon ig ig-bill"></i>
                        <div class="c-icon-text__value">
                            Provision: <?php echo $realty->getCommission() ?? 'Keine Provision hinterlegt'; ?>
                        </div>
                    </div>

                    <form action="" class="c-mini-contact" data-js="contact-form" data-js-contact-form="mini">
                        <div class="row c-mini-contact__name-row">
                            <div class="col-6">
                                <div class="input-wrap">
                                    <input type="text" id="firstname" placeholder=" " required>
                                    <label for="firstname">Vorname</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-wrap">
                                    <input type="text" id="lastname" placeholder=" " required>
                                    <label for="lastname">Nachname</label>
                                </div>
                            </div>
                        </div>
                        <div class="row c-mini-contact__details-row">
                            <div class="col-6">
                                <div class="input-wrap">
                                    <input type="email" id="email" placeholder=" " required>
                                    <label for="email">E-Mail Adresse</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-wrap">
                                    <input type="text" id="phone" placeholder=" ">
                                    <label for="phone">Telefonnummer</label>
                                </div>
                            </div>
                        </div>

                        <div class="c-checkbox">
                            <input type="checkbox" name="toc" id="toc">
                            <label for="toc" class="text-small">
                                Ich stimme der Datenschutzerklärung und einer Kontaktaufnahme durch IGEL Immobilien GmbH
                                per
                                E-Mail oder Telefon für Rückfragen oder zu Informationszwecken zu.
                            </label>
                        </div>

                        <button type="submit">
                            Expose anfordern
                            <i class="button--after ig ig-arrow"></i>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>


