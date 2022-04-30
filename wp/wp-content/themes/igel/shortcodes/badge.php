<?php
/**
 * Created by PhpStorm.
 * User: markusreis
 * Date: 03.05.19
 * Time: 09:59
 */

if (!function_exists('badge_sc')):

    function badge_sc($atts, $content = null)
    {

        /**
         * @var string $atf
         */
        extract(shortcode_atts(
                    array(), $atts
                ));

        //==|=========================================================================================================
        //   |   Set Values
        //----V---------------------------------------------------------------------------------------------------------


        //==|=========================================================================================================
        //   |   Output
        //----V---------------------------------------------------------------------------------------------------------

        ob_start();
        ?>

        <div class="c-badge">
            <div class="c-badge__inner">
                <div class="c-badge__top"><?php echo do_shortcode($content); ?></div>
            </div>
        </div>

        <?php
        return do_shortcode(ob_get_clean());

    }

    add_shortcode('badge', 'badge_sc');

endif;