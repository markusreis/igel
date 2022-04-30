<?php
/**
 * Created by PhpStorm.
 * User: markusreis
 * Date: 03.05.19
 * Time: 09:59
 */

if (!function_exists('share_sc')):

    function share_sc($atts, $content = null)
    {

        /**
         * @var string $url
         */
        extract(shortcode_atts(
                    array(
                        'url' => '',
                    ), $atts
                ));

        //==|=========================================================================================================
        //   |   Set Values
        //----V---------------------------------------------------------------------------------------------------------

        $url = empty($url) ? get_permalink() : $url;

        //==|=========================================================================================================
        //   |   Output
        //----V---------------------------------------------------------------------------------------------------------

        ob_start();
        ?>

        <div class="c-share">
            <div class="c-share__title">Diese Seite teilen:</div>
            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url); ?>"
               class="c-share__el c-share__el--facebook">
                <i class="ig ig-facebook"></i>
                Facebook
            </a>
            <a target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url); ?>"
               class="c-share__el c-share__el--twitter">
                <i class="ig ig-twitter"></i>
                Twitter
            </a>
            <a target="_blank" href="whatsapp://send?text="<?php echo urlencode($url); ?>"
            class="c-share__el c-share__el--whatsapp">
            <i class="ig ig-brand-whatsapp"></i>
            WhatsApp
            </a>
        </div>

        <?php
        return do_shortcode(ob_get_clean());

    }

    add_shortcode('share', 'share_sc');

endif;