<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ThemeReplace
 */

?>

    <span id="pagename" data-name="NewBuild"></span>
    <div class="c-hero c-hero--img">
        <?php
        $gallery = get_field('gallery');
        if (!empty($gallery)):
            ?>
            <div class="c-gallery__icon-toggle" data-action="open-gallery" data-p="<?php echo get_the_ID(); ?>">
                Bilder (<?php echo count($gallery); ?>)
                <i class="ig ig-image"></i>
            </div>
            <?php
            $id = $gallery[0]['ID'];
            ?>
            <picture class="c-hero__bg">
                <img alt="<?php echo get_the_title(); ?> Thumbnail"
                     src="<?php echo wp_get_attachment_image_url($id) ?>"
                     srcset="<?php echo wp_get_attachment_image_srcset($id) ?>"/>
            </picture>
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
                        Projekt
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

    <section class="content">
        <?php
        igTitle(null, get_field('post_code') . ' ' . get_field('place'), 'h1');
        ?>

        <div class="c-tabs__content">
            <div class="c-tabs__content__inner c-tabs__content__inner--active" data-slug="overview">
                <?php
                the_content();
                ?>
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
                                echo '<ul>';
                                foreach ($tab['rows'] as $row) {
                                    echo '<li>Todo: add styling and image - ' . $row['text'] . '</li>';
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
$user = get_field('ansprechpartner');

if (!empty($user)):
    ?>

    <div class="content">
        <?php echo do_shortcode('[divider]'); ?>
    </div>
    <section class="content cols-reverse row@lg c-two-cols c-two-cols--agents">
        <div class="col-12 col-5@lg">
            <div class="picture--cover picture--h-full">
                <?php
                $img = get_field('portrait', 'user_' . $user['ID']);
                if (!empty($img)) {
                    ?>
                    <img alt="<?php echo $user['display_name']; ?> Portrait"
                         src="<?php echo wp_get_attachment_image_url($img['ID']); ?>"
                         srcset="<?php echo wp_get_attachment_image_srcset($img['ID']); ?>"/>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col-12 col-1@lg"></div>
        <div class="col-12 col-6@lg">
            <?php igTitle($user['display_name'], 'Ihr Ansprechpartner', 'div'); ?>

            <?php
            if (!empty($phone = get_user_meta($user['ID'], 'phone')) && !empty($phone[0])) :
                ?>
                <div class="c-icon-text">
                    <i class="c-icon-text__icon ig ig-phone"></i>
                    <a class="c-icon-text__value" href="tel:<?php echo $phone[0]; ?>">
                        <?php echo $phone[0]; ?>
                    </a>
                </div>
            <?php
            endif;
            ?>

            <div class="c-icon-text">
                <i class="c-icon-text__icon ig ig-mail"></i>
                <a class="c-icon-text__value" href="mailto:<?php echo $user['user_email']; ?>">
                    <?php echo $user['user_email']; ?>
                </a>
            </div>

            <div class="c-icon-text">
                <i class="c-icon-text__icon ig ig-bill"></i>
                <div class="c-icon-text__value">
                    Provision: <?php echo get_field('provision'); ?>
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
                        Ich stimme der Datenschutzerklärung und einer Kontaktaufnahme durch IGEL Immobilien GmbH per
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
<?php endif; ?>