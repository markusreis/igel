<?php
/**
 * Template Name: Kontakt-Seite
 * @package igel
 */

if (have_posts()) the_post();

get_header();
hero();
?>
    <span id="contact" data-name="Contact"></span>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php
            if (!empty(get_the_content())) {
                echo '<section class="content">';
                the_content();
                echo '</section>';
            }

            //==|===============================================================================
            //  |  CONTACT
            //--V-------------------------------------------------------------------------------
            $data = get_field('contact');
            if (!empty($data)):
                ?>
                <section class="content--gray c-contact-form" data-scroll-target="contact">
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
                        <button class="c-contact-form__submit" type="submit">
                            Nachricht senden<i class="button--after ig ig-arrow"></i>
                        </button>
                    </form>
                </section>
            <?php
            endif;

            $data = get_field('contact_after');

            if (!empty($data) && !empty($data['text'])):
                ?>
                <section class="content">
                    <?php igTitle($data['section_title'], $data['pretext']); ?>
                    <?php
                    echo do_shortcode($data['text']);
                    ?>
                </section>
            <?php
            endif;
            ?>


        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
