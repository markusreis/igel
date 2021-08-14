<?php
/**
 * Template Name: Verkaufen-Seite
 * @package igel
 */

if (have_posts()) the_post();

get_header();

$data = get_field('about_page_settings');
?>
    <span id="pagename" data-name="Sell"></span>
    <div class="c-hero c-hero--green c-hero--with-box">
        <div class="c-hero__brand">
            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-box-white.svg'; ?>"
                 alt="IGEL Logo weiß">
        </div>
        <div class="content">
            <?php igTitle(get_field('section_title'), get_field('pretext'), 'h1'); ?>
        </div>
        <div class="c-hero__overlay"></div>
    </div>


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

            <?php
            if (!empty(get_the_content())) {
                echo '<section class="content">';
                the_content();
                echo '</section>';
            }
            ?>

            <?php
            //==|===============================================================================
            //  |  SERVICES SECTION ONE
            //--V-------------------------------------------------------------------------------
            $data = get_field('services_one');
            if (!empty($data)):
                ?>
                <section class="content">
                    <?php igTitle($data['section_title'], $data['pretext']); ?>

                    <ol class="c-services row">
                        <?php
                        foreach ($data['service_points'] as $ol):
                            echo '<li class="c-services__el">';
                            echo '<span class="c-services__title text-pretitle">' . $ol['title'] . '</span>';
                            echo '<span class="c-services__text">' . $ol['text'] . '</span>';
                            echo '</li>';
                        endforeach;
                        ?>
                    </ol>

                    <?php echo do_shortcode('[divider]'); ?>
                </section>
            <?php
            endif;
            ?>

            <?php
            //==|===============================================================================
            //  |  INQUIRIES
            //--V-------------------------------------------------------------------------------
            $data = get_field('inquiries');
            if (!empty($data)):
                ?>
                <section class="content">
                    <?php igTitle($data['section_title'], $data['pretext']); ?>

                    <?php
                    ob_start();
                    echo '[accordion]';
                    foreach (['Einfamillienhaus in Klagenfurt', 'Einfamillienhaus in Klagenfurt', 'Einfamillienhaus in Klagenfurt', 'Einfamillienhaus in Klagenfurt'] as $text):
                        echo '[accordion_element title="' . $text . '"]Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.[/accordion_element]';
                    endforeach;
                    echo '[/accordion]';
                    echo do_shortcode(ob_get_clean());
                    ?>

                    <?php echo do_shortcode('[divider]'); ?>
                </section>
            <?php
            endif;
            ?>

            <?php
            //==|===============================================================================
            //  |  SERVICES SECTION TWO
            //--V-------------------------------------------------------------------------------
            $data = get_field('services_two');
            if (!empty($data)):
                ?>
                <section class="content">
                    <?php igTitle($data['section_title'], $data['pretext']); ?>

                    <ol class="c-services row">
                        <?php
                        foreach ($data['service_points'] as $ol):
                            echo '<li class="c-services__el">';
                            echo '<span class="c-services__title text-pretitle">' . $ol['title'] . '</span>';
                            echo '<span class="c-services__text">' . $ol['text'] . '</span>';
                            echo '</li>';
                        endforeach;
                        ?>
                    </ol>

                </section>
            <?php
            endif;
            ?>

            <?php
            //==|===============================================================================
            //  |  CONTACT
            //--V-------------------------------------------------------------------------------
            $data = get_field('contact');
            if (!empty($data)):
                ?>
                <section class="content--gray c-contact-form">
                    <div class="content">
                        <?php igTitle($data['section_title'], $data['pretext']); ?>
                    </div>
                    <form class="c-hero__box c-hero__box--content c-contact-form__form" data-js="contact-form"
                          data-js-contact-form="default">
                        <div class="row">
                            <div class="col-12 col-4@lg">
                                <div class="input-wrap">
                                    <input id="contact-name" placeholder=" " name="contact-name" type="text">
                                    <label for="contact-name">Name</label>
                                </div>
                                <div class="input-wrap">
                                    <input id="contact-mail" placeholder=" " name="contact-mail" type="email">
                                    <label for="contact-mail">E-Mail Adresse</label>
                                </div>
                                <div class="input-wrap">
                                    <input id="contact-phone" placeholder=" " name="contact-phone" type="tel">
                                    <label for="contact-phone">Telefonnummer</label>
                                </div>
                            </div>
                            <div class="col-12 col-8@lg c-contact-form__text-area-wrap">
                                <div class="input-wrap input-wrap--textarea">
                                        <textarea name="contact-message" placeholder=" " id="contact-message"
                                                  rows="6"></textarea>
                                    <label for="contact-message">Ihre Nachricht an uns</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit">
                            Nachricht senden<i class="button--after ig ig-arrow"></i>
                        </button>
                    </form>
                </section>
            <?php
            endif;
            ?>

            <?php
            //==|===============================================================================
            //  |  AVG
            //--V-------------------------------------------------------------------------------
            $data = get_field('av_section');
            if (!empty($data)):
                ?>
                <section class="content cols-reverse row@lg c-two-cols">
                    <div class="col-12 col-5@lg">
                        <picture class="picture--cover picture--h-full">
                            <source
                                    srcset="<?php echo $data['picture_mobile']['sizes']['medium_large']; ?> 320w,
                <?php echo $data['picture_mobile']['sizes']['large']; ?> 660w,
                <?php echo $data['picture_desktop']['url']; ?> 1024w"
                            />
                            <img src="<?php echo $data['picture_mobile']['sizes']['medium_large']; ?>"
                                 alt="Teamwork Personen">
                        </picture>
                        <div class="button">
                            Kontakt aufnehmen
                            <i class="button--after ig ig-arrow"></i>
                        </div>
                    </div>
                    <div class="col-12 col-1@lg"></div>
                    <div class="col-12 col-6@lg">
                        <?php igTitle($data['section_title'], $data['pretext']); ?>
                        <?php
                        if (isset($data['service_points']) && is_array($data['service_points'])) {
                            echo '<ul>';
                            echo array_reduce($data['service_points'], function ($all, $e) {
                                return $all .= '<li>' . $e['text'] . '</li>';
                            },                '');
                            echo '</ul>';
                        }
                        ?>
                    </div>
                </section>
            <?php
            endif;
            ?>


        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
