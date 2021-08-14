<?php
/**
 * Created by PhpStorm.
 * User: markusreis
 * Date: 03.05.19
 * Time: 09:59
 */

if (!function_exists('title_sc')):

    function title_sc($atts, $content = null)
    {

        /**
         * @var string $pretitle
         * @var string $wrap
         */
        extract(shortcode_atts(
                    array(
                        'pretitle' => '',
                        'wrap'     => 'h2'
                    ), $atts
                ));

        ob_start();
        igTitle($content, $pretitle);
        echo ob_get_clean();
    }

    add_shortcode('title', 'title_sc');

endif;