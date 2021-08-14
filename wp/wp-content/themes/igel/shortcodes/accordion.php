<?php
/**
 * Created by PhpStorm.
 * User: markusreis
 * Date: 03.05.19
 * Time: 09:59
 */

if (!function_exists('accordion_sc')):

    function accordion_sc($atts, $content = null)
    {
        return '<div class="c-accordion">' . do_shortcode($content) . '</div>';
    }

    add_shortcode('accordion', 'accordion_sc');

endif;

if (!function_exists('accordion_element_sc')):

    function accordion_element_sc($atts, $content = null)
    {
        /**
         * @var string $title
         * @var string $href
         */
        extract(shortcode_atts(
                    array(
                        'title' => 'Titel muss gesetzt sein',
                        'href'  => '',
                    ), $atts
                ));
        $hasContent = !empty($content);

        if (empty($href)) {
            return '<div class="c-accordion__el' . ($hasContent ? ' c-accordion__el--has-content' : '') . '"><div class="c-accordion__title">' . $title . '</div>' . ($hasContent ? '<div class="c-accordion__content"><div class="c-accordion__content__inner">' . $content . '</div></div>' : '') . '</div>';
        } else {
            return '<a class="c-accordion__el" href="' . $href . '"><span class="c-accordion__title">' . $title . '</span></a>';
        }
    }

    add_shortcode('accordion_element', 'accordion_element_sc');

endif;