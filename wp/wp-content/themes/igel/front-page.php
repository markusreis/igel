<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ThemeReplace
 */


use Igel\Admin\Sync;
use Igel\Cache\DatabaseCache;
use Igel\Services\RealtyPostService;

get_header();

hero('Sie wollen verkaufen?', 'Wir bewerten Ihre Immobilie. Sofort und kostenlos.');

?>

    <span id="pagename" data-name="Homepage"></span>


    <div class="c-hero__box-wrap">
        <div class="c-hero__box">
            <?php echo do_shortcode('[evaluation_form config="evaluationRequest"]'); ?>

            <div class="c-hero__box__overlay-wrap">
                <div class="c-hero__box__overlay"></div>
            </div>
        </div>
    </div>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            if (!empty(get_the_content())) {
                echo '<section class="content">';
                the_content();
                echo '</section>';
                echo '<div class="content">' . do_shortcode('[divider]') . '</div>';
            }
            ?>

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
                            <?php
                            $settings = get_field('newbuild_settings');
                            $title = isset($settings['section_title']) && !empty($settings['section_title']) ? $settings['section_title'] : $newbuild->post_title;
                            $pretitle = isset($settings['pretext']) && !empty($settings['pretext']) ? $settings['pretext'] : get_field('post_code', $newbuild->ID) . ' ' . get_field('place', $newbuild->ID);
                            igTitle($title, $pretitle);
                            ?>
                            <span class="mobile-short-text">
                                <?php
                                echo $newbuild->post_content;
                                ?>
                            </span>
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
                <div class="c-button-title row">
                    <div class="c-button-title__title col-12 col-8@xl">
                        <?php
                        $settings = get_field('buy_settings');
                        $title = isset($settings['section_title']) && !empty($settings['section_title']) ? $settings['section_title'] : 'Entdecken Sie unser Immobilienangebot';
                        $pretitle = isset($settings['pretext']) && !empty($settings['pretext']) ? $settings['pretext'] : 'Igel verkauft';
                        igTitle($title, $pretitle);
                        ?>
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
                    DatabaseCache::clear();
                    $offers = igel()->justImmo()->all();

                    $realtyIds = array_reduce((array)$offers, function ($all, $single) {
                        $all[] = $single->getId();
                        return $all;
                    }, []);
                    global $wpdb;
                    $args = array_merge([RealtyPostService::REMOTE_ID_KEY], $realtyIds);
                    $preparedStatement = $wpdb->prepare("SELECT p.ID, m.meta_value FROM " . $wpdb->prefix . "posts AS p INNER JOIN " . $wpdb->prefix . "postmeta AS m ON p.ID = m.post_id WHERE p.post_status='publish' AND m.meta_key = %s AND m.meta_value IN (" . implode(', ', array_fill(0, count($offers), '%d')) . ")", $args);
                    $posts = $wpdb->get_results($preparedStatement);
                    $postLookup = [];
                    foreach ($posts as $post) {
                        $postLookup[$post->meta_value] = $post->ID;
                    }

                    foreach ($offers as $k => $offer):

                        if (!isset($postLookup[$offer->getId()])) {
                            continue;
                        }

                        $created = $offer->getCreatedAt('U');
                        $twoWeeksAgo = (new DateTime())->modify('-2 week')->format('U');

                        $badgeText = $offer->getStatus() !== 'aktiv' ? $offer->getStatus() : '';
                        $badgeText = empty($badgeText) && ($created > $twoWeeksAgo || isset($offer->getCategories()[21820])) ? 'Neu' : $badgeText;


                        if ($k > 2) {
                            break;
                        }
                        /** @var Justimmo\Model\Realty $offer */

                        $created = $offer->getCreatedAt('U');
                        $twoWeeksAgo = (new DateTime())->modify('-2 week')->format('U');

                        $badgeText = $offer->getStatus() !== 'aktiv' ? $offer->getStatus() : '';
                        $badgeText = empty($badgeText) && ($created > $twoWeeksAgo || isset($offer->getCategories()[21820])) ? 'Neu' : $badgeText;
                        ?>
                        <a href="<?php echo get_permalink($postLookup[$offer->getId()]); ?>"
                           class="c-immo-list__el">
                            <div class="c-immo-list__el__inner">
                                <div class="c-immo-list__el__image-wrap">
                                    <div class="c-immo-list__el__image-wrap-inner">
                                        <?php
                                        $mainImage = $offer->getPictures('TITELBILD');
                                        if (!empty($mainImage)) {
                                            $mainImage = array_shift($mainImage);
                                            /** @var \Justimmo\Model\Attachment $mainImage */
                                            echo '<img class="c-immo-list__el__thumbnail" src="' . $mainImage->getUrl('medium') . '" alt="' . esc_html($offer->getTitle()) . ' Thumbnail"/>';
                                        }
                                        if (!empty($badgeText)) {
                                            echo '<div class="c-immo-list__el__badge">' . $badgeText . '</div>';
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    if (isset($offer->getCategories()[21829])) {
                                        echo '<span class="overlay-image -left"><img src="' . get_stylesheet_directory_uri() . '/assets/img/klimaaktiv.png' . '" alt="Klimaaktiv Logo"></span>';
                                    }
                                    if (isset($offer->getCategories()[21817])) {
                                        echo '<img src="http://igel-immobilien.at.docker/wp-content/themes/igel/assets/img/wdf-schleife.png" alt="Wohn dich frei Schleife" class="c-immo-list__el__wdf" style="opacity: 1;">';
                                    }
                                    ?>
                                </div>
                                <div class="c-immo-list__el__price text-small">
                                    <?php
                                    $isRent = !$offer->getMarketingType()['KAUF'];
                                    $price = ig_price(!$isRent ? $offer->getPurchasePrice() : $offer->getTotalRent());
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
                                        '' => $offer->getZipCode() . ' ' . $offer->getPlace(),
                                        'm<sup>2</sup> Wohnfläche' => $offer->getLivingArea(),
                                        'Zimmer' => $offer->getRoomCount(),
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

            <?php
            wp_reset_postdata();
            $data = get_field('hemmadorf_settings');
            if ($data) :
                ?>

                <section class="content">
                    <div class="c-button-title row">
                        <div class="c-button-title__title col-12 col-8@xl">
                            <?php igTitle($data['section_title'], $data['pretext']); ?>
                        </div>
                        <div class="col-12 col-4@xl">
                            <a href="https://hemmadorf.at/" target="_blank" class="button h-full">
                                Zum Hemmadorf
                                <i class="button--after ig ig-arrow"></i>
                            </a>
                        </div>
                    </div>

                    <?php

                    ?>
                    <picture class="c-frontpage__services-image">
                        <img alt="Services Titelbild"
                             src="<?php echo wp_get_attachment_thumb_url($data['hemmadorf_image']); ?>"
                             srcset="<?php echo wp_get_attachment_image_srcset($data['hemmadorf_image']); ?>"/>
                    </picture>

                    <div>
                        <?php echo $data['hemmadorf_text']; ?>
                    </div>

                    <div class="content">
                        <?php echo do_shortcode('[divider]'); ?>
                    </div>


                </section>

            <?php
            endif;

            $data = get_field('services');
            if ($data) :
                ?>
                <section class="content">
                    <div class="c-button-title row">
                        <div class="c-button-title__title col-12 col-8@xl">
                            <?php igTitle($data['section_title'], $data['pretext']); ?>
                        </div>
                        <div class="col-12 col-4@xl">
                            <a href="<?php echo igPagelink('employees'); ?>" class="button h-full">
                                Mehr über uns
                                <i class="button--after ig ig-arrow"></i>
                            </a>
                        </div>
                    </div>

                    <div>
                        <?php echo $data['description']; ?>
                    </div>

                    <div class="c-icon-boxes">
                        <?php
                        ob_start();
                        foreach ($data['points'] as $row):
                            echo '<div class="c-icon-boxes__box">';
                            echo '<i class="c-icon-boxes__icon ig ig-' . $row['icon'] . '">';
                            echo '</i>';
                            echo '<div class="c-icon-boxes__title">';
                            echo $row['title'];
                            echo '</div>';
                            echo '</div>';
                        endforeach;
                        echo do_shortcode(ob_get_clean());
                        ?>
                    </div>

                </section>

            <?php
            endif;
            ?>

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
