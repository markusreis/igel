<?php

if (!function_exists('render_agents')) {
    function render_agents($users)
    {
        ?>
        <div class="c-agents">
            <?php
            foreach ($users as $user):
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
        <?php
    }
}