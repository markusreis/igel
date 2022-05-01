<?php
/**
 * Created by PhpStorm.
 * User: markusreis
 * Date: 03.05.19
 * Time: 09:59
 */

if (!function_exists('evaluation_form_sc')):

    function evaluation_form_sc($atts, $content = null)
    {

        /**
         * @var string $config
         */
        extract(shortcode_atts(
            array(
                'config' => 'evaluationRequest' // or "searchRequest"
            ), $atts
        ));

        //==|=========================================================================================================
        //   |   Set Values
        //----V---------------------------------------------------------------------------------------------------------


        $initalPlaceholder = $config === 'evaluationRequest' ? 'Ihre Adresse...' : 'In welcher Region suchen sie?';
        $buttonText = $config === 'evaluationRequest' ? 'Jetzt bewerten' : 'Suchauftrag erstellen';


        //==|=========================================================================================================
        //   |   Output
        //----V---------------------------------------------------------------------------------------------------------

        ob_start();
        ?>
        <form class="c-evaluation" data-config="<?php echo $config; ?>">
            <div class="c-evaluation__steps">
                <div class="c-evaluation__step c-evaluation__step--initial" data-active="true">
                    <div class="input-wrap">
                        <input type="text" data-field="initial" placeholder=" ">
                        <label for="bewerten-adresse"><?php echo $initalPlaceholder; ?></label>
                    </div>
                    <button type="submit" data-action="next">
                        <?php echo $buttonText; ?><i class="button--after ig ig-arrow"></i>
                    </button>
                </div>
            </div>
        </form>
        <?php
        return do_shortcode(ob_get_clean());

    }

    add_shortcode('evaluation_form', 'evaluation_form_sc');

endif;