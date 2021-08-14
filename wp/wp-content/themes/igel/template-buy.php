<?php
/**
 * Template Name: Kaufen-Seite
 * @package igel
 */

use Igel\Admin\Sync;
use Igel\Services\RealtyPostService;

if (have_posts()) the_post();

$regions  = igel()->justImmo()->data()->get('regions');
$zipCodes = igel()->justImmo()->data()->get('zipCodes');
$types    = igel()->justImmo()->data()->get('types');

foreach ($types as $pk => $type) {
    $types[$pk] = $type['name'];
}

$old     = isset($_GET) && !empty($_GET) ? $_GET : [];
$options = [
    'region'  => $regions,
    'type'    => $types,
    'buyrent' => [
        'miete' => 'Miete',
        'kauf'  => 'Kauf',
    ],
    'rooms'   => [
        '1' => '1+',
        '2' => '2+',
        '3' => '3+',
        '4' => '4+',
        '5' => '5+',
        '6' => '6+',
    ],
];

function printOptions($key, $options, $old)
{
    $value    = 'all';
    $name     = 'Alle';
    $selected = isset($old[$key]) && $old[$key] . '' === $value . '';
    echo '<option value="' . $value . '"' . ($selected ? ' selected' : '') . '>' . $name . '</option>';

    foreach ($options[$key] as $value => $name) {
        $selected = isset($old[$key]) && $old[$key] . '' === $value . '';
        echo '<option value="' . $value . '"' . ($selected ? ' selected' : '') . '>' . $name . '</option>';
    }
}


if (!isset(getallheaders()['X-Ajax']) || getallheaders()['X-Ajax'] !== 'internal') :
    get_header();

    $data = get_field('about_page_settings');
    ?>
    <span id="pagename" data-name="Buy"></span>
    <div class="c-hero c-hero--green c-hero--with-box">
        <div class="c-hero__brand">
            <img src="<?php echo get_stylesheet_directory_uri() . '/assets/img/brand-box-white.svg'; ?>"
                 alt="IGEL Logo weiß">
        </div>
        <div class="content">
            <?php igTitle(get_field('section_title'), get_field('pretext'), 'h1'); ?>
        </div>
        <div class="c-hero__overlay"></div>
    </div>
    <div class="c-hero__box-wrap">
        <div class="c-hero__box c-buy-filter__wrap">
            <form class="c-buy-filter" action="<?php echo get_permalink(); ?>" method="get">
                <div>
                    <div class="input-wrap input-wrap--select">
                        <select id="region" name="region" required>
                            <?php printOptions('region', $options, $old); ?>
                        </select>
                        <label for="region">Bezirk</label>
                    </div>
                </div>
                <div>
                    <div class="input-wrap input-wrap--select">
                        <select id="type" name="type" required>
                            <?php printOptions('type', $options, $old); ?>
                        </select>
                        <label for="type">Objektart</label>
                    </div>
                </div>
                <div>
                    <div class="input-wrap input-wrap--select">
                        <select id="buyrent" name="buyrent" required>
                            <?php printOptions('buyrent', $options, $old); ?>
                        </select>
                        <label for="buyrent">Mieten oder kaufen</label>
                    </div>
                </div>
                <div>
                    <div class="input-wrap input-wrap--select">
                        <select id="rooms" name="rooms" required>
                            <?php printOptions('rooms', $options, $old); ?>
                        </select>
                        <label for="rooms">Anzahl der Zimmer</label>
                    </div>
                </div>
                <div>
                    <div class="input-wrap">
                        <input type="number" id="price_min" name="price_min"
                               placeholder=" "<?php echo empty($old['price_min']) ? '' : ' value="' . $old['price_min'] . '"'; ?>>
                        <label for="price_min">Preis von</label>
                    </div>
                </div>
                <div>
                    <div class="input-wrap">
                        <input type="number" id="price_max" name="price_max"
                               placeholder=" "<?php echo empty($old['price_max']) ? '' : ' value="' . $old['price_max'] . '"'; ?>>
                        <label for="price_max">Preis bis</label>
                    </div>
                </div>
                <button type="submit">Filter anwenden<i class="button--after ig ig-arrow"></i></button>
            </form>
            <div class="c-hero__box__overlay-wrap">
                <div class="c-hero__box__overlay"></div>
            </div>
        </div>
    </div>

    <div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    if (!empty(get_the_content())) {
        echo '<section class="content">';
        the_content();
        echo '</section>';
    }

endif;
?>

    <section class="content c-immo-list">

        <?php
        $hasAnyFilters = false;
        $offers        = igel()->justImmo()->query();

        foreach ($options as $key => $value) {
            if (isset($_GET[$key]) && !empty($_GET[$key]) && $_GET[$key] !== 'all') {

                $value         = $_GET[$key];
                $hasAnyFilters = true;

                switch ($key) {

                    case 'region':
                        // TODO Does not work
                        $value    = (int)$value;
                        $zipCodes = array_filter($zipCodes, function ($code) use ($value) {
                            return $code['regionId'] === $value;
                        });
                        break;
                    case 'type':
                        $offers->filterByRealtyTypeId($value);
                        break;
                    case 'buyrent':
                        if ($value === 'kauf') {
                            $offers->filterByBuy(true);
                        } else {
                            $offers->filterByRent(true);
                        }
                        break;
                    case 'rooms':
                        $offers->filterByRooms(array('min' => (int)$value));
                        break;
                }
            }
        }

        if (
            (isset($_GET['price_min']) && !empty($_GET['price_min'])) ||
            (isset($_GET['price_max']) && !empty($_GET['price_max']))
        ) {
            $hasAnyFilters = true;
            $offers->filterByPrice(array('min' => (int)$_GET['price_min'], 'max' => empty($_GET['price_max']) ? 9999999999999 : (int)$_GET['price_max']));
        }


        if (!$hasAnyFilters) {
            $offers = igel()->justImmo()->all();
        } else {
            $offers = $offers->setLimit(999999)->find();
        }

        $realtyIds = array_reduce((array)$offers, function ($all, $single) {
            $all[] = $single->getId();
            return $all;
        },                        []);

        if (!empty($realtyIds)) {

            global $wpdb;
            $args              = array_merge([RealtyPostService::REMOTE_ID_KEY], $realtyIds);
            $preparedStatement = $wpdb->prepare("SELECT p.ID, m.meta_value FROM " . $wpdb->prefix . "posts AS p INNER JOIN " . $wpdb->prefix . "postmeta AS m ON p.ID = m.post_id WHERE m.meta_key = %s AND m.meta_value IN (" . implode(', ', array_fill(0, count($offers), '%d')) . ")", $args);
            $posts             = $wpdb->get_results($preparedStatement);

            if (count($offers) !== count($posts)) {
                Sync::getInstance()->run(true);

                $args              = array_merge([RealtyPostService::REMOTE_ID_KEY], $realtyIds);
                $preparedStatement = $wpdb->prepare("SELECT p.ID, m.meta_value FROM " . $wpdb->prefix . "posts AS p INNER JOIN " . $wpdb->prefix . "postmeta AS m ON p.ID = m.post_id WHERE m.meta_key = %s AND m.meta_value IN (" . implode(', ', array_fill(0, count($offers), '%d')) . ")", $args);
                $posts             = $wpdb->get_results($preparedStatement);
            }

            $postLookup = [];
            foreach ($posts as $post) {
                $postLookup[$post->meta_value] = $post->ID;
            }

            foreach ($offers as $offer):
                /** @var Justimmo\Model\Realty $offer */

                // TODO: MAKE BADGE
                $badgeText = $offer->getStatus() !== 'aktiv' ? $offer->getStatus() : '';
                ?>
                <a href="<?php echo get_permalink($postLookup[$offer->getId()]); ?>"
                   class="c-immo-list__el">
                    <div class="c-immo-list__el__inner">
                        <div class="c-immo-list__el__image-wrap">
                            <?php
                            $mainImage = $offer->getPictures('TITELBILD');
                            if (!empty($mainImage)) {
                                $mainImage = array_shift($mainImage);
                                /** @var \Justimmo\Model\Attachment $mainImage */
                                echo '<img src="' . $mainImage->getUrl('medium') . '" alt="' . esc_html($offer->getTitle()) . ' Thumbnail"/>';
                            }
                            if (!empty($badgeText)) {
                                echo '<div class="c-immo-list__el__badge">' . $badgeText . '</div>';
                            }
                            ?>
                        </div>
                        <div class="c-immo-list__el__price text-small">
                            <?php
                            $isRent = !$offer->getMarketingType()['KAUF'];
                            $price  = ig_price(!$isRent ? $offer->getPurchasePrice() : $offer->getTotalRent());
                            echo $isRent ? 'Zu vermieten: ' : 'Zu verkaufen: ';
                            echo empty($price) ? 'Preis auf Anfrage' : "$price";
                            ?>
                        </div>
                        <div class="c-immo-list__el__title text-big">
                            <?php echo $offer->getTitle(); ?>
                        </div>
                        <ul class="c-immo-list__el__details text-small">
                            <?php
                            $data = [
                                ''                         => $offer->getZipCode() . ' ' . $offer->getPlace(),
                                'm<sup>2</sup> Wohnfläche' => $offer->getLivingArea(),
                                'Zimmer'                   => $offer->getRoomCount(),
                            ];
                            foreach ($data as $name => $value) {
                                if (!empty($value)) {
                                    echo "<li>$value $name</li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </a>
            <?php
            endforeach;
        } else {
            ?>
            <div class="c-immo-list__nothing-found">
                Leider haben wir keine passenden Immobilien gefunden...
            </div>
            <?php
        }
        ?>

    </section>

<?php
if (!isset(getallheaders()['X-Ajax']) || getallheaders()['X-Ajax'] !== 'internal') :
    ?>

    </main><!-- #main -->
    </div><!-- #primary -->

    <?php
    get_footer();
endif;