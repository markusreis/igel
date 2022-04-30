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

                    <?php
                    $users = get_users(
                        [
                            'meta_key' => 'menu_order',
                            'number'   => 9999,
                            'orderby'  => 'meta_value_num',
                            'order'    => 'ASC'
                        ]
                    );

                    $users = array_filter($users, function ($user) {
                        return get_field('show_agent', 'user_' . $user->ID) === true;
                    });

                    render_agents($users);
                    ?>

                </section>
            <?php
            endif;
            ?>


        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
