<?php

if (!function_exists('render_ansprechpartner')) {

    function render_ansprechpartner($user, $provision = null)
    {
        ?>
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
                        Provision: <?php echo empty($provision) ? 'Nicht angegeben' : $provision; ?>
                    </div>
                </div>

                <?php
                render_mini_contact_form($user);
                ?>
            </div>
        </section>
        <?php
    }
}