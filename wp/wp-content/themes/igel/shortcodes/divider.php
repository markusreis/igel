<?php
/**
 * Created by PhpStorm.
 * User: markusreis
 * Date: 03.05.19
 * Time: 09:59
 */

if (!function_exists('divider_sc')):

    function divider_sc($atts, $content = null)
    {
        return '<div class="c-divider"><img src="' . get_stylesheet_directory_uri() . '/assets/img/brand-divider.svg" alt="IGEL Logo - Section-Trenner"/></div>';
    }

    add_shortcode('divider', 'divider_sc');

endif;