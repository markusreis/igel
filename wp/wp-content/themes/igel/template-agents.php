<?php
/**
 * Template Name: Makler-Seite
 * @package igel
 */

if (have_posts()) the_post();

get_header();
hero();

$data = get_field('about_page_settings');
?>
    <span id="pagename" data-name="Agents"></span>

    <div class="c-hero__box-wrap">
        <div class="c-hero__box">
            <div class="c-highlights">
                <?php
                $details = get_field('overview_facts') ?? [];
                foreach ($details as $i => $row):
                    ?>
                    <div class="c-highlights__el<?php echo $i > 3 ? ' c-highlights__el--desktop' : ''; ?>">
                        <div class="text-highlight text-main">
                            <i class="ig ig-<?php echo $row['icon']; ?>"></i>
                            <?php echo $row['value']; ?>
                        </div>
                        <div class="c-highlights__el__desc">
                            <?php echo $row['description']; ?>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>
            </div>
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
                echo do_shortcode('<div class="content">[divider]</div>');
            }
            ?>

            <?php
            //==|===============================================================================
            //  |  About Company
            //--V-------------------------------------------------------------------------------
            $data = get_field('company');
            if (!empty($data)):
                ?>
                <section class="content cols row@lg c-two-cols c-two-cols--agents">
                    <div class="col-12 col-6@lg">
                        <?php igTitle($data['section_title'], $data['pretext']); ?>
                        <?php
                        if (isset($data['list']) && is_array($data['list'])) {
                            echo '<div class="c-about-list">';
                            foreach ($data['list'] as $row) {
                                echo '<div class="c-about-list__el"><div class="c-about-list__title text-big">' . $row['title'] . '</div>' . $row['text'] . '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <div class="col-12 col-1@lg"></div>
                    <div class="col-12 col-5@lg">
                        <picture class="picture--cover picture--h-full">
                            <source
                                    srcset="<?php echo $data['picture_mobile']['sizes']['medium_large']; ?> 320w,
                <?php echo $data['picture_mobile']['sizes']['large']; ?> 660w,
                <?php echo $data['picture_desktop']['url']; ?> 1024w"
                            />
                            <img src="<?php echo $data['picture_mobile']['sizes']['medium_large']; ?>"
                                 alt="IGEL Immobilien Über Uns">
                        </picture>
                    </div>
                </section>

                <div class="content">
                    <?php echo do_shortcode('[divider]'); ?>
                </div>
            <?php
            endif;
            ?>

            <?php
            //==|===============================================================================
            //  |  Agent List
            //--V-------------------------------------------------------------------------------
            $data = get_field('agents');
            if (!empty($data)):
                ?>
                <section class="content">

                    <?php igTitle($data['section_title'], $data['pretext']); ?>

                    <div class="c-agents">
                        <?php
                        $users = get_users(
                            [
                                'meta_key' => 'menu_order',
                                'number'   => 9999,
                                'orderby'  => 'meta_value_num',
                                'order'    => 'ASC'
                            ]
                        );
                        foreach ($users as $user):
                            if (!get_field('show_agent', 'user_' . $user->ID)) {
                                continue;
                            }
                            /** @var WP_User $user */
                            ?>
                            <div class="c-agents__el">
                                <div class="c-agents__img">
                                    <?php
                                    $img = get_field('landscape', 'user_' . $user->ID);
                                    if (!empty($img)) {
                                        ?>
                                        <img alt="<?php echo $user->display_name; ?> IGEL Immobilien Portrait"
                                             src="<?php echo wp_get_attachment_image_url($img['ID']) ?>"
                                             srcset="<?php echo wp_get_attachment_image_srcset($img['ID']) ?>"/>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="c-agents__name text-big">
                                    <?php echo $user->display_name; ?>
                                </div>
                                <div class="c-agents__position">
                                    <?php echo get_field('role', 'user_' . $user->ID); ?>
                                </div>
                                <div class="c-agents__contact">
                                    <?php
                                    if (!empty($phone = get_user_meta($user->ID, 'phone')) && !empty($phone[0])) :
                                        ?>
                                        <div class="c-agents__contact__el c-agents__contact__el--phone">
                                            <i class="ig ig-phone"></i>
                                            <a class="c-agents__contact__el__link"
                                               href="tel:<?php echo $phone[0]; ?>"><?php echo $phone[0]; ?></a>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                    <div class="c-agents__contact__el<?php echo empty($phone) ? ' c-agents__contact__el--full' : ''; ?>">
                                        <i class="ig ig-mail"></i>
                                        <a class="c-agents__contact__el__link"
                                           href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach;
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
