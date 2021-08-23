<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ThemeReplace
 */

get_header();

hero('Sie wollen verkaufen?', 'Wir bewerten Ihre Immobilie. Sofort und kostenlos.');

?>

    <span id="pagename" data-name="Homepage"></span>


    <div class="c-hero__box-wrap">
        <div class="c-hero__box">
            <form class="c-evaluation">
                <div class="c-evaluation__steps">
                    <div class="c-evaluation__step c-evaluation__step--initial" data-active="true">
                        <div class="input-wrap">
                            <input type="text" id="bewerten-adresse" placeholder=" ">
                            <label for="bewerten-adresse">Ihre Adresse...</label>
                        </div>
                        <button type="submit" data-action="next">
                            Jetzt bewerten<i class="button--after ig ig-arrow"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="c-hero__box__overlay-wrap">
                <div class="c-hero__box__overlay"></div>
            </div>
        </div>
    </div>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <div class="content">

                <?php
                ob_start();

                $newbuild = get_posts(['post_type' => 'newbuild', 'numberposts' => 1, 'orderby' => 'DATE', 'order' => 'DESC']);
                $newbuild = empty($newbuild) ? null : array_shift($newbuild);
                if (!empty($newbuild)) {
                    ?>
                    <section class="content cols-reverse row@lg c-two-cols">
                        <div class="col-12 col-5@lg">
                            <picture class="picture--cover picture--h-full">

                                <?php
                                if (!empty($gallery = get_field('gallery', $newbuild))) :
                                    $id = $gallery[0]['ID'];
                                    ?>
                                    <img alt="<?php echo $newbuild->post_title; ?> Thumbnail"
                                         src="<?php echo wp_get_attachment_image_url($id) ?>"
                                         srcset="<?php echo wp_get_attachment_image_srcset($id) ?>"/>
                                <?php
                                endif;
                                ?>
                            </picture>
                            <a href="<?php echo get_permalink($newbuild); ?>" class="button">
                                Zum Projekt
                                <i class="button--after ig ig-arrow"></i>
                            </a>
                        </div>
                        <div class="col-12 col-1@lg"></div>
                        <div class="col-12 col-6@lg">
                            <?php igTitle($newbuild->post_title, get_field('post_code', $newbuild->ID) . ' ' . get_field('place', $newbuild->ID)); ?>
                            <?php
                            echo $newbuild->post_content;
                            ?>
                            <div class="c-highlights -mt-35 -mt-45@lg">
                                <?php
                                $details = get_field('overview_facts', $newbuild) ?? [];
                                foreach ($details as $i => $row):
                                    if ($i > 2) {
                                        break;
                                    }
                                    ?>
                                    <div class="c-highlights__el<?php echo $i === 2 ? ' c-highlights__el--desktop' : ''; ?>">
                                        <div class="text-highlight text-main">
                                            <?php echo $row['value']; ?>
                                        </div>
                                        <div class="text-small">
                                            <?php echo $row['description']; ?>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </section>
                    <?php
                }
                $newbuildSection = ob_get_clean();
                ?>
            </div>

            <?php
            ob_start();
            ?>

            <section class="content">
                <div class="c-frontpage__button-title row">
                    <div class="c-frontpage__button-title__title col-12 col-8@xl">
                        <?php igTitle('Entdecken Sie unser Immobilenangebot', 'Igel verkauft'); ?>
                    </div>
                    <div class="col-12 col-4@xl">
                        <a href="<?php echo igPagelink('realties'); ?>" class="button h-full">
                            Komplettes Angebot
                            <i class="button--after ig ig-arrow"></i>
                        </a>
                    </div>
                </div>

                <div class="c-immo-list">

                    <?php
                    $offers = igel()->justImmo()->all();

                    foreach ($offers as $k => $offer):

                        if ($k > 2) {
                            break;
                        }
                        /** @var Justimmo\Model\Realty $offer */

                        // TODO: MAKE BADGE
                        $badgeText = $offer->getStatus() !== 'aktiv' ? $offer->getStatus() : '';
                        ?>
                        <a href="<?php echo get_home_url() . '/kaufen/' . sanitize_title($offer->getTitle()); ?>"
                           class="c-immo-list__el">
                            <div class="c-immo-list__el__inner">
                                <div class="c-immo-list__el__image-wrap">
                                    <?php
                                    $mainImage = $offer->getPictures('TITELBILD');
                                    if (!empty($mainImage)) {
                                        $mainImage = array_shift($mainImage);
                                        /** @var \Justimmo\Model\Attachment $mainImage */
                                        echo '<img src="' . $mainImage->getUrl('medium') . '" alt="' . esc_html($offer->getTitle()) . ' Thumbnail"/>';
                                    }
                                    ?>
                                </div>
                                <div class="c-immo-list__el__price text-small">
                                    <?php
                                    $isRent = !$offer->getMarketingType()['KAUF'];
                                    $price  = ig_price(!$isRent ? $offer->getPurchasePrice() : $offer->getTotalRent());
                                    echo $isRent ? 'Zu vermieten: ' : 'Zu verkaufen: ';
                                    echo empty($price) ? 'Preis auf Anfrage' : "$price";
                                    ?>
                                </div>
                                <div class="c-immo-list__el__title text-big">
                                    <?php echo $offer->getTitle(); ?>
                                </div>
                                <ul class="c-immo-list__el__details text-small">
                                    <?php
                                    $data = [
                                        ''                         => $offer->getZipCode() . ' ' . $offer->getPlace(),
                                        'm<sup>2</sup> Wohnfläche' => $offer->getLivingArea(),
                                        'Zimmer'                   => $offer->getRoomCount(),
                                    ];
                                    foreach ($data as $name => $value) {
                                        if (!empty($value)) {
                                            echo "<li>$value $name</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </a>
                    <?php
                    endforeach;
                    ?>

                </div>


                <a href="<?php echo igPagelink('realties'); ?>" class="button mobile-all-immo">
                    Komplettes Angebot
                    <i class="button--after ig ig-arrow"></i>
                </a>
            </section>

            <?php
            $immoSection = ob_get_clean();


            if (get_field('promote_newbuild')) {
                echo $newbuildSection;
                echo '<div class="content">' . do_shortcode('[divider]') . '</div>';
                echo $immoSection;
            } else {
                echo $immoSection;
                echo '<div class="content">' . do_shortcode('[divider]') . '</div>';
                echo $newbuildSection;
            }
            ?>

            <div class="content">
                <?php echo do_shortcode('[divider]'); ?>
            </div>

            <section class="content">
                <div class="c-frontpage__button-title row">
                    <div class="c-frontpage__button-title__title col-12 col-8@xl">
                        <?php igTitle('Unser Serviceangebot', 'Das machen wir für Sie'); ?>
                    </div>
                    <div class="col-12 col-4@xl">
                        <a href="<?php echo igPagelink('employees'); ?>" class="button h-full">
                            Mehr über uns
                            <i class="button--after ig ig-arrow"></i>
                        </a>
                    </div>
                </div>

                <?php

                ?>
                <picture class="c-frontpage__services-image">
                    <img alt="Services Titelbild"
                         src="<?php echo wp_get_attachment_thumb_url(get_field('services_image')); ?>"
                         srcset="<?php echo wp_get_attachment_image_srcset(get_field('services_image')); ?>"/>
                </picture>


                <?php
                $serivces = get_field('sell', 'options');
                $data     = array_slice(array_merge(get_field('services_one', $serivces->ID)['service_points'], get_field('services_two', $serivces->ID)['service_points']), 0, 6);

                ob_start();
                echo '[accordion]';
                foreach ($data as $row):
                    echo '[accordion_element title="' . $row['title'] . '"]' . $row['text'] . '[/accordion_element]';
                endforeach;
                echo '[/accordion]';
                echo do_shortcode(ob_get_clean());

                ?>

            </section>

            <section class="content--gray">
                <div class="content">

                    <?php igTitle('Unsere Partner', 'Schenken auch Sie uns Ihr vertrauen'); ?>

                    <div class="c-partners" id="partner-list">
                        <div class="c-partners__list">
                            <div class="c-partners__list__inner">
                                <?php
                                $partners = get_field('partners');

                                foreach ($partners as $partner) {
                                    $data = $partner['details'];
                                    echo empty($data['url']) ? '<div class="c-partners__el">' : '<a class="c-partners__el" href="' . $data['url'] . '" target="_blank" title="' . esc_html($data['name']) . '">';
                                    echo '<img src="' . $partner['logo']['sizes']['medium'] . '" title="' . esc_html($data['name']) . '" alt="' . $data['name'] . ' Logo">';
                                    echo empty($data['url']) ? '</div>' : '</a>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="c-partners__toggle text-pretitle" data-action="toggle-partners">
                            Alle zeigen
                        </div>
                    </div>
                </div>
            </section>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
