<?php
/**
 * Template Name: Neubauprojekte-Seite
 * @package igel
 */

if (have_posts()) the_post();

get_header();
hero();

$data = get_field('about_page_settings');
?>
    <span id="pagename" data-name="NewBuilds"></span>

<?php
$projects = get_posts(['post_type' => 'newbuild', 'numberposts' => -1, 'orderby' => 'date']);
if (!empty($projects)):
    $project = array_shift($projects);
    ?>
    <div class="c-hero__box-wrap">
        <div class="c-hero__box c-hero__box--mobile-shadow">

            <div class="c-new-build-highlight">

                <div class="c-new-build-highlight__image-wrap">
                    <?php
                    if (!empty($gallery = get_field('gallery', $project))) :
                        $id = $gallery[0]['ID'];
                        ?>
                        <img alt="<?php echo $project->post_title; ?> Thumbnail"
                             src="<?php echo wp_get_attachment_image_url($id) ?>"
                             srcset="<?php echo wp_get_attachment_image_srcset($id) ?>"/>
                    <?php
                    endif;
                    ?>

                </div>

                <div class="c-new-build-highlight__bottom">
                    <div class="c-highlights">
                        <?php
                        $details = get_field('overview_facts', $project) ?? [];
                        foreach ($details as $i => $row):
                            ?>
                            <div class="c-highlights__el<?php echo $i > 2 ? ' c-highlights__el--mobile' : ''; ?>">
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
                    <a href="<?php echo get_permalink($project); ?>" class="button -mt-20 -mt-0@lg">
                        <?php echo $project->post_title; ?><i class="button--after ig ig-arrow"></i>
                    </a>
                </div>
            </div>
            <div class="c-hero__box__overlay-wrap">
                <div class="c-hero__box__overlay"></div>
            </div>
        </div>
    </div>
<?php
endif;
?>

    <div class="content">
        <?php
        echo '<div class="c-divider c-divider--mt-0 c-divider--desktop"><img src="' . get_stylesheet_directory_uri() . '/assets/img/brand-divider.svg" alt="IGEL Logo - Section-Trenner"/></div>';
        ?>
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


            <section class="content">

                <?php
                $data = get_field('list');
                if (!empty($data)) {
                    igTitle($data['section_title'], $data['pretext']);
                }
                ?>

                <div class="c-new-builds">
                    <?php
                    foreach ($projects

                             as $project):
                        ?>
                        <div class="c-new-builds__el">
                            <div class="c-new-builds__el__image-wrap">
                                <?php
                                if (!empty($gallery = get_field('gallery', $project))) :
                                    ?>
                                    <img alt="<?php echo $project->post_title; ?> Thumbnail"
                                         src="<?php echo $gallery[0]['sizes']['newbuildthumb']; ?>"/>
                                <?php
                                endif;
                                ?>
                            </div>
                            <a href="<?php echo get_permalink($project); ?>" class="c-new-builds__el__button button">
                                <?php echo $project->post_title; ?>, <?php echo get_field('place', $project) ?>
                                <i class="button--after ig ig-arrow"></i>
                            </a>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>

            </section>

            <?php
            //==|===============================================================================
            //  |  A-Z Section
            //--V-------------------------------------------------------------------------------
            $data = get_field('info');
            if (!empty($data)):
                ?>
                <section class="content">

                    <?php echo do_shortcode('[divider]'); ?>

                    <?php igTitle($data['section_title'], $data['pretext']); ?>

                    <?php
                    echo $data['text'];
                    ?>
                    <br/>
                    <br/>
                    <?php
                    ob_start();
                    echo '[accordion]';
                    foreach ($data['list'] as $row):
                        echo '[accordion_element title="' . $row['title'] . '"]' . $row['text'] . '[/accordion_element]';
                    endforeach;
                    echo '[/accordion]';
                    echo do_shortcode(ob_get_clean());
                    ?>
                </section>
            <?php
            endif;
            ?>


        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
