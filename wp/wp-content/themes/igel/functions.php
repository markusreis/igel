<?php
/**
 * markusreis functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package igel
 */

//==|===============================================================================
//  |  JS / CSS
//--V-------------------------------------------------------------------------------

# Inline Styles
use Igel\Services\RealtyPostService;
use Justimmo\Model\Realty;

add_action('wp_head', function () {
    foreach (glob(__DIR__ . '/assets/dist/*.css') as $file) {
        if (strpos($file, 'inline') !== false) {
            echo '<style>';
            include($file);
            echo '</style>';
        }
    }
});

# Styles and scripts
function theme_load_custom_admin_scripts()
{
    wp_enqueue_style('custom_wp_admin_css', get_template_directory_uri() . '/assets/scss/admin-style.css', false, '1.0.0');
}

add_action('admin_enqueue_scripts', 'theme_load_custom_admin_scripts');

# Admin Styles and Scripts
function theme_load_custom_scripts()
{
    foreach (glob(__DIR__ . '/assets/dist/*.css') as $k => $file) {
        if (strpos($file, 'inline') === false) {
            $uri = get_stylesheet_directory_uri() . str_replace(glob(__DIR__), '', $file);
            wp_enqueue_style('theme_load_style_' . $k, $uri, false, '1.0.0');
        }
    }
    foreach (glob(__DIR__ . '/assets/dist/*.js') as $k => $file) {
        if (strpos($file, 'inline') === false) {
            $uri = get_stylesheet_directory_uri() . str_replace(glob(__DIR__), '', $file);
            wp_enqueue_script('theme_load_js_' . $k, $uri, false, '1.0.0');
        }
    }
}

add_action('wp_head', function () {
    $contactPage = get_field('contact', 'options');
    $contactPageUrl = empty($contactPage) ? '' : get_permalink($contactPage);
    echo '<script>';
    echo 'var igelData = {
        apiBase: "' . rtrim(get_home_url(), '/') . '/wp-json/igel/",
        contactUrl: "' . $contactPageUrl . '"
    };';
    echo '</script>';
});

add_action('wp_enqueue_scripts', 'theme_load_custom_scripts');


/**************************************************
 *                  LOAD FILES
 *************************************************/

# ACF
add_action('acf/init', function () {
    foreach (glob(__DIR__ . '/custom-fields/*.php') as $file) {
        $file = str_replace('bitnami/wordpress/', '', $file);
        include_once($file);
    }
});

# Shortcodes
foreach (glob(__DIR__ . '/shortcodes/*.php') as $file) {
    $file = str_replace('bitnami/wordpress/', '', $file);
    include_once($file);
}


add_theme_support('menus');

function register_menus()
{
    register_nav_menus(
        array(
            'main' => 'Haupt-Navigation',
            'footer_company' => 'Footer: Unternehmen',
            'footer_legal' => 'Footer: Rechtliches',
        ));
}

add_action('after_setup_theme', 'register_menus');


# Add Custom Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
            'menu_title' => 'Theme Settings',
            'capability' => 'manage_options',
        )
    );
    acf_add_options_page(
        array(
            'menu_title' => 'Suchaufträge',
            'capability' => 'manage_options',
        )
    );
}

/**************************************************
 *                  WP NORMALIZE
 *************************************************/

remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('template_redirect', 'wp_shortlink_header', 11);

function normalize_cleanup_query_string($src)
{
    $parts = explode('?', $src);
    return $parts[0];
}

add_filter('script_loader_src', 'normalize_cleanup_query_string', 15, 1);
add_filter('style_loader_src', 'normalize_cleanup_query_string', 15, 1);

function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    //add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
    //add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
}

add_action('init', 'disable_emojis');

function disable_embeds_code_init()
{

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');

    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
    add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');

    // Remove all embeds rewrite rules.
    add_filter('rewrite_rules_array', 'disable_embeds_rewrites');

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
}

add_action('init', 'disable_embeds_code_init', 9999);

function disable_embeds_tiny_mce_plugin($plugins)
{
    return array_diff($plugins, array('wpembed'));
}

function disable_embeds_rewrites($rules)
{
    foreach ($rules as $rule => $rewrite) {
        if (false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
        }
    }
    return $rules;
}


/**************************************************
 *                  FILE HANDLING
 *************************************************/

# Allow SVG Upload
add_filter('upload_mimes', 'allow_svg');
function allow_svg($mime)
{
    $mime['svg'] = 'image/svg+xml';
    return $mime;
}

add_filter('nav_menu_css_class', function ($classes) {
    return in_array('current_page_item', $classes) ? ['nav--active'] : [];
});

/**************************************************
 *                  TEMPLATE PARTS
 *************************************************/

if (!function_exists('igTitle')) {
    function igTitle($t = null, $pre = '', $wrap = 'h2')
    {
        $t = empty($t) ? get_the_title() : $t;
        echo empty($pre) ? '' : "<div class=\"text-pretitle\">$pre</div>";
        echo "<$wrap class=\"text-title\">$t</$wrap>";
    }
}
if (!function_exists('igPagelink')) {
    /**
     * @param $type 'realties' or 'newbuilds' or 'employees' or 'sell'
     * @return false|string|WP_Error
     */
    function igPagelink($type)
    {
        return get_permalink(get_field($type, 'options'));
    }
}

function add_style_select_buttons($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}

// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'add_style_select_buttons');

//add custom styles to the WordPress editor
function my_custom_styles($init_array)
{

    $style_formats = array(
        // These are the custom styles
        array(
            'title' => 'Pretitle',
            'block' => 'div',
            'classes' => 'red-button',
            'wrapper' => true,
        ),
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode($style_formats);

    return $init_array;

}

// Attach callback to 'tiny_mce_before_init'
add_filter('tiny_mce_before_init', 'my_custom_styles');


function getTitleFields($a, $b)
{
    return [

        array(
            'key' => $b,
            'label' => 'Pretext',
            'name' => 'pretext',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
        array(
            'key' => $a,
            'label' => 'Titel',
            'name' => 'section_title',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
        ),
    ];
}


add_image_size('newbuildthumb', 400, 300, true);

add_filter('user_contactmethods', function ($methods) {
    return [
        'phone' => 'Telefonnummer',
    ];
});


add_action('rest_api_init', function () {
    register_rest_route('igel', '/eval', array(
        'methods' => 'POST',
        'callback' => 'igel_eval_form'
    ));
    register_rest_route('igel', '/contact', array(
        'methods' => 'POST',
        'callback' => 'igel_contact_form'
    ));
    register_rest_route('igel', '/inserat', array(
        'methods' => 'POST',
        'callback' => 'igel_inserat_form'
    ));
    register_rest_route('igel', '/realty-gallery', array(
        'methods' => 'GET',
        'callback' => 'igel_get_realty_gallery'
    ));
});

function igel_get_realty_gallery(\WP_REST_Request $request)
{
    if (
        (!$request->has_param('r') || !(($realty = igel()->justImmo()->query()->findPk($request->get_param('r'))) instanceof Realty)) &&
        (!$request->has_param('p') || !(($post = get_post($request->get_param('p'))) instanceof WP_Post))
    ) {
        return [];
    }

    if (!empty($post)) {
        return array_map(function ($media) use ($post) {
            return '<picture data-img="' . $media['ID'] . '"><img alt="' . esc_html($post->post_title) . '" src="' . wp_get_attachment_image_url($media['ID']) . '" srcset="' . wp_get_attachment_image_srcset($media['ID']) . '"/></picture>';
        }, get_field('gallery', $post->ID));
    } else {
        $rs = RealtyPostService::getInstance();
        return $rs->getMediaHtml($realty);
    }
}

function igel_contact_form(\WP_REST_Request $request)
{
    $data = $request->get_params();

    foreach (['contact-name', 'contact-mail', 'contact-phone', 'contact-message'] as $param) {
        if (!isset($data[$param]) || empty($data[$param])) {
            header("HTTP/1.1 400 Bad Request");
            exit();
        }
    }
    try {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $res = wp_mail(
            array(get_field('default_recipient', 'options')),
            'Homepage: Kontaktformular',
            '<strong>' . $data['contact-name'] . '</strong> (<strong>Email</strong>: ' . $data['contact-mail'] . ', <strong>Telefon</strong>: ' . $data['contact-phone'] . ') schrieb: <br/><br/>' . $data['contact-message'],
            $headers
        );
        return $res;
    } catch (Exception$e) {
        header("HTTP/1.1 500 Server Error");
        exit();
    }
}

function igel_inserat_form(\WP_REST_Request $request)
{
    $data = $request->get_params();

    foreach (['firstname', 'lastname', 'email', 'phone', 'toc'] as $param) {
        if (!isset($data[$param]) || empty($data[$param])) {
            header("HTTP/1.1 400 Bad Request");
            exit();
        }
    }
    try {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        if ($data['agent'] && ($user = get_user_by('id', $data['agent']))) {
            $recipient = $user->user_email;
        } else {
            $recipient = get_field('default_recipient', 'options');
        }

        if (strpos($data['url'], 'neubauprojekte') !== false) {
            $recipient = 'klagenfurt@igel-immobilien.at';
        }

        $content = 'Ein Kunde hat Interesse an folgendem Inserat: ' . $data['url'] . '<br/><br/>';
        $content .= '<strong>Name:</strong> ' . $data['firstname'] . ' ' . $data['lastname'] . '<br/>';
        $content .= '<strong>E-Mail:</strong> ' . $data['email'] . '<br/>';
        $content .= '<strong>Telefon:</strong> ' . $data['phone'] . '<br/>';

        $res = wp_mail(
            array($recipient),
            'Homepage: Interesse an Inserat',
            $content,
            $headers
        );
        return $res;
    } catch (Exception$e) {
        header("HTTP/1.1 500 Server Error");
        exit();
    }
}

function igel_eval_form(\WP_REST_Request $request)
{
    $data = $request->get_params();

    try {

        $content = '';

        foreach ($data as $row) {
            switch ($row['type']) {
                case 'headline':
                    $content .= '<div style="font-size:22px;font-weight:bold;">' . $row['title'] . '</div>';
                    break;
                case 'sectionTitle':
                    $content .= '<div style="font-size:18px;font-weight:bold;margin-top:25px;margin-bottom:10px;">' . $row['title'] . '</div>';
                    break;
                case 'fieldValue':
                    $content .= '<strong>' . $row['title'] . '</strong>: ' . $row['value'] . '<br/>';
                    break;
            }
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');

        $res = wp_mail(
            array(get_field('default_recipient', 'options')),
            'Homepage Kontaktformular',
            $content,
            $headers
        );
        return $res;
    } catch (Exception$e) {
        header("HTTP/1.1 500 Server Error");
        exit();
    }
}


function hero($titleFallback = '', $pretitleFallback = '', $hasBox = true)
{

    $heroBgDesktop = get_field('hero_bg_desktop');
    $heroBgMobile = get_field('hero_bg_mobile');
    $hasBg = !empty($heroBgDesktop) && !empty($heroBgMobile);

    ?>
    <div class="c-hero <?php echo $hasBg ? 'c-hero--img' : 'c-hero--green'; ?> <?php echo !$hasBox ? 'c-hero--simple' : 'c-hero--with-box'; ?>">

        <?php if (!$hasBg) : ?>

            <div class="c-hero__igel">
                <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/igel-shape-white.png'; ?>"
                     alt="Igel weiß">
            </div>
            <!--<div class="c-hero__brand">
                <img src="echo get_stylesheet_directory_uri() . '/assets/img/igel-shape-white.png'; ?>"
                     alt="Igel weiß">
            </div>-->

        <?php else : ?>
            <?php
            $srcArr = [
                '300w' => $heroBgMobile['sizes']['medium'],
                '400w' => isset($heroBgMobile['sizes']['newbuildthumb']) ? $heroBgMobile['sizes']['newbuildthumb'] : null,
                '768w' => $heroBgMobile['sizes']['medium_large'],
                '1024w' => $heroBgDesktop['sizes']['large'],
                '1536w' => $heroBgDesktop['sizes']['1536x1536'],
                '2000w' => $heroBgDesktop['url'],
            ];


            $srcArr = array_filter($srcArr, function ($el) {
                return !empty($el);
            });

            $srcSet = '';
            foreach ($srcArr as $key => $src) {
                $srcSet .= $src . ' ' . $key . ', ';
            }
            $srcSet = rtrim($srcSet, ', ');
            ?>

            <picture class="c-hero__bg">
                <img alt="<?php echo get_the_title(); ?> Titelbild"
                     src="<?php echo wp_get_attachment_image_url($heroBgMobile['ID']); ?>"
                     srcset="<?php echo $srcSet; ?>"/>
            </picture>

        <?php endif; ?>

        <div class="content">
            <?php
            $t = get_field('section_title');
            $p = get_field('pretext');
            igTitle(empty($t) ? $titleFallback : $t, empty($p) ? $pretitleFallback : $p, 'h1');
            ?>
        </div>
        <div class="c-hero__overlay"></div>

    </div>
    <?php
}