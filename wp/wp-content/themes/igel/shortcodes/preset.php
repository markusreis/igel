<?php
/**
 * Created by PhpStorm.
 * User: markusreis
 * Date: 03.05.19
 * Time: 09:59
 */

if (!function_exists('preset_sc')):

    function preset_sc($atts, $content = null)
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

        <?php
        return do_shortcode(ob_get_clean());

    }

    add_shortcode('preset', 'preset_sc');

endif;