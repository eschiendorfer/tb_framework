<?php

/**
 * Copyright (C) 2021 Emanuel Schiendorfer
 *
 * @author    Emanuel Schiendorfer <https://github.com/eschiendorfer>
 * @copyright 2021 Emanuel Schiendorfer
 */


class FrameworkController extends FrontController {

    // Supported Components
    const COMPONENT_LIST_COMPACT = [
        'type' => 'list',
        'name' => 'list_compact',
    ];

    const COMPONENT_FLEXBOX_COMPONENTS = [
        'type' => 'flexbox',
        'name' => 'flexbox_components',
    ];

    const COMPONENT_IMAGECLOUD_DEFAULT = [
        'type' => 'imagecloud',
        'name' => 'imagecloud_default',
    ];

    const COMPONENT_IMAGECLOUD_PROMO = [
        'type' => 'imagecloud',
        'name' => 'imagecloud_promo',
    ];

    const COMPONENT_HEADER_DEFAULT = [
        'type' => 'header',
        'name' => 'header_default',
    ];

    const COMPONENT_FEATURE_DEFAULT = [
        'type' => 'feature',
        'name' => 'feature_default',
    ];

    const COMPONENT_CARD_DEFAULT = [
        'type' => 'card',
        'name' => 'card_default',
    ];

    const COMPONENT_CARD_PROMO = [
        'type' => 'card',
        'name' => 'card_promo',
    ];

    const COMPONENT_CARD_SIMPLE = [
        'type' => 'card',
        'name' => 'card_simple',
    ];

    const COMPONENT_CARD_PRODUCT = [
        'type' => 'card',
        'name' => 'card_product',
    ];



    const COMPONENT_MODAL_DEFAULT = [
        'type' => 'modal',
        'name' => 'modal_default',
    ];

    const COMPONENT_CAROUSEL_COMPONENTS = [
        'type' => 'carousel',
        'name' => 'carousel_components',
    ];

    const COMPONENT_CAROUSEL_PROMO = [
        'type' => 'carousel',
        'name' => 'carousel_promo',
    ];

    const COMPONENT_TAB_COMPONENTS = [
        'type' => 'tab',
        'name' => 'tab_components',
    ];

    const COMPONENT_MENU_VERTICAL = [
        'type' => 'menu',
        'name' => 'menu_vertical',
    ];

    const COMPONENT_MENU_HORIZONTAL = [
        'type' => 'menu',
        'name' => 'menu_horizontal',
    ];


    // List: Submenu vertical


    // Dropdowns (Button/Menu)

    // Pagination
    // Tooltips/Popover (see Bootstrap)
    // Accordion (UI KIT/Fomantic)
    // comments (UI Kit/Fomantic)
    // Check the use of section (UI Kit)
    // Somehow having a login popup (modal_login probably)

    // Supported CSS Elements


    // Basic Elements
    // Todo: Colors
    /* const CSS_COLOR_PRIMARY = 'color_primary';
    /const CSS_COLOR_SECONDARY = 'color_secondary';
    const CSS_COLOR_TERTIARY = 'color_tertiary';
    const CSS_COLOR_GRAY = 'color_gray';
    const CSS_COLOR_GRAY_LIGHT = 'color_gray_light';
    const CSS_COLOR_GRAY_DARK = 'color_gray_dark';
    const CSS_COLOR_BLACK = 'color_black';*/ // Often times a clean black is too hard...

    // Buttons
    const ELEMENT_BUTTON_PRIMARY = [
        'name' => 'button_primary',
        'css_selector' => 'tbfw_button tbfw_button_primary',
    ];

    const ELEMENT_BUTTON_SECONDARY = [
        'name' => 'button_secondary',
        'css_selector' => 'tbfw_button tbfw_button_secondary',
    ];

    const ELEMENT_BUTTON_TERTIARY = [
        'name' => 'button_tertiary',
        'css_selector' => 'tbfw_button tbfw_button_tertiary',
    ];

    const ELEMENT_BUTTON_CTA = [
        'name' => 'button_cta',
        'css_selector' => 'tbfw_button tbfw_button_cta',
    ];

        // Button Sizes
        const ELEMENT_BUTTON_SIZE_LARGE = [
            'name' => 'button_large',
            'css_selector' => 'tbfw_button_large',
        ];
        const ELEMENT_BUTTON_SIZE_SMALL = [
            'name' => 'button_small',
            'css_selector' => 'tbfw_button_small',
        ];

    // Links
    const ELEMENT_LINK_CTA = [
        'name' => 'link_cta',
        'css_selector' => 'tbfw_link_cta tbfw_link_cta',
    ];

    // Badges
    const ELEMENT_BADGE_DEFAULT = [
        'name' => 'badge_default',
        'css_selector' => 'tbfw_badge tbfw_badge_default',
    ];
    const ELEMENT_BADGE_SUCCESS = [
        'name' => 'badge_success',
        'css_selector' => 'tbfw_badge tbfw_badge_success',
    ];
    const ELEMENT_BADGE_WARNING = [
        'name' => 'badge_warning',
        'css_selector' => 'tbfw_badge tbfw_badge_warning',
    ];
    const ELEMENT_BADGE_DANGER = [
        'name' => 'badge_danger',
        'css_selector' => 'tbfw_badge tbfw_badge_danger',
    ];
        // Badge Sizes
        const ELEMENT_BADGE_SIZE_LARGE = [
            'name' => 'badge_large',
            'css_selector' => 'tbfw_badge_large',
        ];
        const ELEMENT_BADGE_SIZE_SMALL = [
            'name' => 'badge_small',
            'css_selector' => 'tbfw_badge_small',
        ];


    // Alerts / Notifications
    const ELEMENT_ALERT_DEFAULT = [
        'name' => 'alert_default',
        'css_selector' => 'tbfw_alert tbfw_alert_default',
    ];

    const ELEMENT_ALERT_SUCCESS = [
        'name' => 'alert_success',
        'css_selector' => 'tbfw_alert tbfw_alert_success',
    ];

    const ELEMENT_ALERT_WARNING = [
        'name' => 'alert_warning',
        'css_selector' => 'tbfw_alert tbfw_alert_warning',
    ];

    const ELEMENT_ALERT_DANGER = [
        'name' => 'alert_danger',
        'css_selector' => 'tbfw_alert tbfw_alert_danger',
    ];


    // Spinners
    const ELEMENT_SPINNER_DEFAULT = [
        'name' => 'spinner_default',
        'css_selector' => 'tbfw_spinner_default',
    ];

        // Spinner Sizes
        const ELEMENT_SPINNER_SIZE_LARGE = [
            'name' => 'spinner_large',
            'css_selector' => 'tbfw_spinner_large',
        ];
        const ELEMENT_SPINNER_SIZE_SMALL = [
            'name' => 'spinner_small',
            'css_selector' => 'tbfw_spinner_small',
        ];

        // Todo: SIZE_FLEXIBLE -> To use this inside other elements (like a button)


    // Ecommerce Related Elements

    // Prices
    const ELEMENT_PRICE_DEFAULT = [
        'name'  =>  'price_default',
        'css_selector'  =>  'tbfw_price_default',
    ];

    const ELEMENT_PRICE_REDUCED = [
        'name' => 'price_reduced',
        'css_selector'=> 'tbfw_price_reduced',
    ];

    const ELEMENT_PRICE_ORIGINAL = [
        'name' => 'price_original',
        'css_selector' => 'tbfw_price_original',
    ];

        const ELEMENT_PRICE_SIZE_LARGE = [
            'name' => 'price_size_large',
            'css_selector' => 'tbfw_price_size_large',
        ];

        const ELEMENT_PRICE_SIZE_SMALL = [
            'name' => 'price_size_small',
            'css_selector' => 'tbfw_price_size_small',
        ];

    // Stock Information
    const ELEMENT_STOCK_AVAILABLE = [
        'name' => 'stock_available',
        'css_selector' => 'tbfw_stock_available',
    ];

    const ELEMENT_STOCK_NOT_AVAILABLE = [
        'name' => 'stock_not_available',
        'css_selector' => 'tbfw_stock_not_available',
    ];

    const ELEMENT_STOCK_PREORDER = [
        'name' => 'stock_preorder',
        'css_selector' => 'tbfw_stock_preorder',
    ];

    // Tags
    const ELEMENT_TAG_ON_SALE = [
        'name' => 'tag_sale',
        'css_selector' => 'tbfw_tag_sale',
    ];

    const ELEMENT_TAG_NEW = [
        'name' => 'tag_new',
        'css_selector' => 'tbfw_tag_new',
    ];



    // Some other elements to be considered:
        // Close Button
        // Progress Bar
        // Ratings (Fomantic)

    public static $alreadyCalled;

    public $component;
    public $smarty_vars;

    public function __construct($component, $smarty_vars = [], $demo = false) {

        parent::__construct();

        $this->component = $component;

        // Set Smarty_Vars
        $this->smarty_vars = $demo ? $this->getDemoData($component) : $smarty_vars;

    }

    public static function getCssSelectorForElement($selector_element_default, $selector_size_default = '') {

        // Check if the css selector should be overridden due to any value in theme config.xml file
        $configObject = simplexml_load_file(_PS_THEME_DIR_.'config.xml');

        $selector_element = $selector_element_default['css_selector'];

        $selector_size = $selector_size_default['css_selector'] ?? '';


        foreach ($configObject->custom_css_selectors->custom_css_selector as $custom_css_selector) {

            if (($custom_css_selector['name']==$selector_element_default['name']) && $custom_css_selector['custom']) {
                $selector_element = $custom_css_selector['custom'];
            }

            if ($selector_size_default && ($custom_css_selector['name']==$selector_size_default['name']) && $custom_css_selector['custom']) {
                $selector_size = $custom_css_selector['custom'];
            }
        }

        return $selector_size ? $selector_element.' '.$selector_size : $selector_element;
    }


    public static function getAllCssSelectorsForElements() {

        $reflectionClass = new ReflectionClass(__CLASS__);
        $constants = $reflectionClass->getConstants();

        $smarty_css_selectors = [];

        foreach ($constants as $constant_name => $constant_value) {
            if (strpos($constant_name, 'ELEMENT_')!==false) {
                $smarty_css_selectors[$constant_value['name']] = self::getCssSelectorForElement($constant_value);
            }
        }

        return $smarty_css_selectors;
    }

    public static function getFilePathByComponent($component) {

        $type = $component['type'];
        $name = $component['name'];

        $file_path_relative = 'component/'.$type.'/'.$name.'.tpl';

        if (file_exists(_PS_THEME_DIR_.$file_path_relative)) {
            return _PS_THEME_DIR_.$file_path_relative;
        }
        elseif (file_exists(_PS_MODULE_DIR_.'tb_framework/'.$file_path_relative)) {
            return _PS_MODULE_DIR_.'tb_framework/'.$file_path_relative;
        }
        else {
            throw new PrestaShopException("Tpl for Element {$name} not found!");
        }
    }


    // Fetch Components
    public static function fetchElement($component, $data = [], $columns_rewrite = [], $demo_mode = false) {

        $context = Context::getContext();

        if (!empty($columns_rewrite)) {
            $data = self::replaceColumns($data, $columns_rewrite);
        }

        // Main component
        $context->smarty->assign([
            'component' => $data,
            'first_call' => !isset(self::$alreadyCalled[$component['name']]),
        ]);

        if (isset($data['id'])) {
            Media::addJsDef(['id' => $data['id']]);
        }

        self::$alreadyCalled[$component['name']] = true;

        $component_tpl_file = self::getFilePathByComponent($component);

        return $context->smarty->fetch($component_tpl_file);
    }

    public static function fetchElementDemo($component) {
        $data = self::getDemoData($component);
        return self::fetchElement($component, $data);
    }



    private static function replaceColumns($smarty_vars, $rewrite_columns) {

        if (!is_array($smarty_vars) || !is_array($rewrite_columns) || empty($smarty_vars) || empty($rewrite_columns)) {
            return false;
        }

        // This complex function allows to replace columns, which is helpful if you have a given dataset with "wrong" column names
        foreach ($rewrite_columns as $key_input_structures => $key_needed_structures) {

            // Getting input values
            $key_input_structure = explode('.', $key_input_structures);
            $key_input = $key_input_structure[array_key_last($key_input_structure)];
            array_pop($key_input_structure);

            // Getting needed values
            $key_needed_structure = explode('.', $key_needed_structures);
            $key_needed = $key_needed_structure[array_key_last($key_needed_structure)];
            array_pop($key_needed_structure);


            // At the moment we only support column change on maximal three layers
            if (count($key_input_structure)>3 || count($key_needed_structure)>3) {
                return false;
            }

            // This rewrite is so complex, that we implement all possibilities manually (max 3 layers)
            if (!count($key_input_structure)) {
                if (!count($key_needed_structure)) {
                    $smarty_vars[$key_needed] = $smarty_vars[$key_input];
                }
                elseif (count($key_needed_structure)==1) {
                    $smarty_vars[$key_needed_structure[0]][$key_needed] = $smarty_vars[$key_input];
                }
                elseif (count($key_needed_structure)==2) {
                    $smarty_vars[$key_needed_structure[0]][$key_needed_structure[1]][$key_needed] = $smarty_vars[$key_input];
                }
            }
            elseif (count($key_input_structure)===1) {
                foreach ($smarty_vars[$key_input_structure[0]] as $key_layer_1 => $values) {
                    if (!count($key_needed_structure)) {
                        $smarty_vars[$key_needed] = $values[$key_input] ?? $key_input;
                    }
                    elseif (count($key_needed_structure)===1) {
                        $smarty_vars[$key_needed_structure[0]][$key_layer_1][$key_needed] = $values[$key_input] ?? $key_input;
                    }
                    elseif (count($key_needed_structure)===2) {
                        $smarty_vars[$key_needed_structure[0]][$key_layer_1][$key_needed_structure[1]][$key_needed] = $values[$key_input] ?? $key_input;
                    }
                }
            }
            elseif (count($key_input_structure)===2) {

                // Todo: this wasn't tested yet, as its use case is super rare
                foreach ($smarty_vars[$key_input_structure[0]] as $key_layer_1 => $layer) {

                    foreach ($layer[$key_input_structure[1]] as $key_layer_2 => $values) {
                        if (!count($key_needed_structure)) {
                            $smarty_vars[$key_needed] = $values[$key_input];
                        } elseif (count($key_needed_structure) === 1) {
                            $smarty_vars[$key_needed_structure[0]][$key_layer_1][$key_needed] = $values[$key_input];
                        } elseif (count($key_needed_structure) === 2) {
                            $smarty_vars[$key_needed_structure[0]][$key_layer_1][$key_needed_structure[1]][$key_layer_2][$key_needed] = $values[$key_input];
                        }
                    }
                }
            }

        }

        return $smarty_vars;
    }

    private static function replaceColumn() {

    }




    // Demo
    private static function getDemoData($component) {

        $method_name = 'getDemoData_'.$component['name'];

        if (method_exists(__CLASS__, $method_name)) {
            return self::{$method_name}();
        }
        else {
            throw new PrestaShopException("Demo Data Function {$method_name} not found!");
        }

    }

    public function getDemoData_List_compact() {

        $context = Context::getContext();

        $query = new \DbQuery();
        $query->select('p.*, pl.*, cl.name AS category, m.name AS manufacturer');
        $query->from('product', 'p');
        $query->innerJoin('product_shop', 'ps', 'ps.id_product=p.id_product AND ps.id_shop='.$context->shop->id);
        $query->innerJoin('product_lang', 'pl', 'pl.id_product=p.id_product');
        $query->innerJoin('category_lang', 'cl', 'cl.id_category=p.id_category_default AND cl.id_lang='.$context->language->id);
        $query->leftJoin('manufacturer', 'm', 'm.id_manufacturer=p.id_manufacturer AND m.active=1');
        $query->orderBy('p.active DESC, RAND()');
        $query->limit('5');
        $products =\Db::getInstance()->ExecuteS($query);

        $data = [];

        foreach ($products as $product) {

            $manufacturer = $product['manufacturer'] ? ' from '.$product['manufacturer'] : '';

            $price_selling = Tools::displayPrice(Product::getPriceStatic($product['id_product']));
            $price_drop = Tools::displayPrice(5.95);

            $css_price_default = self::getCssSelectorForElement(self::ELEMENT_PRICE_DEFAULT);
            $css_price_reduced = self::getCssSelectorForElement(self::ELEMENT_PRICE_REDUCED);
            $css_price_original = self::getCssSelectorForElement(self::ELEMENT_PRICE_ORIGINAL);

            if ($price_drop) {
                $price_content = "<span class='{$css_price_reduced}'>{$price_selling}</span> <span class='{$css_price_original}'>{$price_drop}</span>";
            }
            else {
                $price_content = "<span class='{$css_price_default}'>{$price_selling}</span>";
            }

            $price_content = '';


            // Info: that is the correct structure of one row
            $data[] = [
                'img' => $context->link->getImageLink($product['link_rewrite'], Product::getCover($product['id_product'])['id_image'], 'small_default'),
                'title' => $product['name'],
                'subtitle' => $product['category'].$manufacturer,
                'link' => ['url' => $context->link->getProductLink($product['id_product'])],
                'element_columns' => [
                ]
            ];
        }

        $titles = ['Brettspiele', 'Puzzles', 'Gesselschaftsspiele', 'Sammelkarten', 'Sammelfiguren', 'Klassiker', 'Kinderspiele', 'Leuchtpuzzles'];

        $demo_data = [
            'title' => $titles[array_rand($titles)],
            'data' => $data,
            'button' => [
                'title' => 'View All',
                'link'  => ['url' => 'https://www.blick.ch'],
                'style' => 'width: 100%;',
            ],
        ];

        return $demo_data;

    }

    // Image Cloud
    public function getDemoData_imagecloud_default() {

        // Todo: how to know how many elements are showed? -> the theme needs to tell
        $manufacturers = Manufacturer::getManufacturers(false, 1, true, 1, 12);

        $data = [
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Boardgames',
                'link' => ['url' => '#'],
            ],
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Card Games',
                'link' => ['url' => '#'],
            ],
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Collectibles',
                'link' => ['url' => '#'],
            ],
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Chess',
                'link' => ['url' => '#'],
            ],
        ];

        /*foreach ($manufacturers as $manufacturer) {
            $data[] = [
                'src' => __PS_BASE_URI__ . 'img/m/' . (int) $manufacturer['id_manufacturer'] . '.jpg',
                'title' => $manufacturer['name'],
                'link' => ['url' => $this->context->link->getManufacturerLink($manufacturer['id_manufacturer'])],
            ];
        }*/


        $demo_data = [
            'title' => 'Handcrafted Brands',
            'data' => $data,
            'button' => [
                'title' => 'View All Brands',
                'link'  => ['url' => 'https://www.blick.ch'],
                'style' => 'width: 100%;',
            ],
        ];

        return $demo_data;
    }

    public function getDemoData_imagecloud_promo() {
        $demo_data = [
            'header' => [
                'title' => 'Klassiker gehen immer oder nicht!?',
                'subtitle' => 'Unsere Lieblingsst??cke',
            ],
            'promo' => [
                'position' => 'right',
            ],
            'data' => [
                ['src' => 'https://www.genzo.ch/810-home_default/jenga.jpg'],
                ['src' => 'https://www.genzo.ch/812-home_default/shut-the-box-4er-variante.jpg'],
                ['src' => 'https://www.genzo.ch/4746-home_default/chinderjass-tschau-sepp.jpg'],
                ['src' => 'https://www.genzo.ch/800-home_default/tiroler-roulette-octagon.jpg'],
                ['src' => 'https://www.genzo.ch/801-home_default/rummy.jpg'],
                ['src' => 'https://www.genzo.ch/802-home_default/kalaha.jpg'],
            ],
            'link' => [
                'title' => 'Alle anzeigen',
                'url' => '#',
            ],
        ];

        return $demo_data;
    }

    // Header
    public function getDemoData_header_default() {

        $demo_data = [
            'title' => 'Die Meisten sind richtig gut',
            'subtitle' => 'Marken & Verlage',
            'description' => 'The market has like one million brands. 99% of them don\'t fit our expectation. Only the best one will be listed on our site...',
        ];

        return $demo_data;
    }

    // Feature
    public function getDemoData_feature_default() {

        $demo_data = [
            'features' => [
                [
                    'title' => 'Tante Emma lebt',
                    'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.',
                    'icon' => '', // Todo: we need a clean icon handling
                ],
                [
                    'title' => 'Keine Drohnenlieferung',
                    'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.',
                    'icon' => '',
                ],
                [
                    'title' => 'Zarin gesucht',
                    'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.',
                    'icon' => '',
                ],
            ],
        ];

        return $demo_data;
    }

    // Cards
    public function getDemoData_card_default() {

        $demo_data = [
            'title' => 'Sozialer als alle sozialen Netzwerke',
            'section' => 'Familienspiele',
            'description' => 'Eltern k??nnen ihren Kindern nichts Sch??neres schenken als gemeinsame Zeit. Brettspiele schweissen zusammen und man erlebt lustige Momente, an die man sich noch Jahre sp??ter erinnert.',
            'image' => [
                'src' => '/themes/genzo_theme/img/cover.png',
            ],
            'link' => [
                'url' => '/blog/something',
            ],
            'footer' => true, // Todo: implement this cleaner -> probably should have some kind of helper components
        ];

        return $demo_data;
    }

    public function getDemoData_card_promo() {

        $demo_data = [
            'title' => 'Sozialer als soziale Netzwerke',
            'subtitle' => 'Familienspiele',
            'description' => 'Eltern k??nnen ihren Kindern nichts Sch??neres schenken als gemeinsame Zeit. Brettspiele schweissen zusammen und man erlebt lustige Momente, an die man sich noch Jahre sp??ter erinnert. F??r einen Spielnachmittag reichen ein paar Snacks, leckere Getr??nke und ein tolles Spiel.',
            'image' => [
                'src' => '/themes/genzo_theme/img/cover.png',
            ],
            'button' => [
                'title' => 'Familienspiele ansehen',
                'link' => 'https://www.spielezar.ch/familienspiele',
            ]
        ];

        return $demo_data;
    }

    public function getDemoData_card_simple() {

        $demo_data = [
            'title' => 'Sozialer als alle sozialen Netzwerke',
            'image' => [
                'src' => '/themes/genzo_theme/img/cover.png',
            ],
            'link' => [
                'url' => '/card_simple/demo'
            ],
        ];

        return $demo_data;
    }

    public function getDemoData_card_product() {

        $products = Product::getProducts(1, 1, 1, 'id_product', 'ASC');
        $product = $products[0];

        $product['id_image'] = Product::getCover($product['id_product'])['id_image'];

        $product = Product::getProductProperties(1, $product);

        return $product;
    }

    // Modal
    public function getDemoData_modal_default() {

        $html = '<b>This is a custom modal</b>';

        $html .= FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_HEADER_DEFAULT);
        $html .= FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_CAROUSEL_COMPONENTS);


        $demo_data = [
            'show' => true, // Should the modal open on page load?
            'close_button' => true, // Should there be a close button on top right?
            'close_background' => true, // Should the modal close, when the user clicks on the background outside the modal?
            'id' => 'modal_unique_id', // Make sure that you chose-something unique
            'title' => 'Custom Modal',
            'html' => $html,
        ];

        return $demo_data;
    }

    // Carousel
    public static function getDemoData_carousel_components() {

        $categories = [
            ['id_category' => 12],
            ['id_category' => 227],
            ['id_category' => 235],
            ['id_category' => 422],
            ['id_category' => 426],
        ];

        $random_element = $categories[array_rand($categories)];
        $random_element['display'] = true;


        $products = Product::getProducts(1, 0, 10, 'id_product', 'ASC', $random_element['id_category'], true);
        $productBoxes = [];

        foreach ($products as &$product) {
            $product['id_image'] = Product::getCover($product['id_product'])['id_image'];
            $productBoxes[] = FrameworkController::fetchElement(FrameworkController::COMPONENT_CARD_PRODUCT, $product);
        }

        // Render the whole component
        $demo_data = [
            'id' => 'products_slider'.rand(0,100),
            'nbr_columns' => 3.5,
            'slides' => $productBoxes
        ];

        return $demo_data;
    }

    public function getDemoData_carousel_promo() {

        $demo_data_carousel = self::getDemoData_carousel_components();

        $demo_data_carousel['nbr_columns'] = 2.5;
        $demo_data_carousel['promo_position'] = 'left';

        // Todo: probably we should add a boolean value for "boxed". This could actually be true for all containers
        // Maybe we can fix it also, if components need to implement a header and a show_all variant.

        return $demo_data_carousel;
    }

    // Menus
    public function getDemoData_menu_vertical() {
        $data = [
            'title' => 'Kategorien',
            'items' => [
                ['title' => 'Brettspiele', 'url' => '/test', 'icon' => ['class' => 'icon-boardgame', 'width' => '20', 'height' => '20']],
                ['title' => 'Puzzle', 'url' => '#', 'icon' => ['class' => 'icon-puzzle']],
                ['title' => 'Trading Cards', 'url' => '#', 'icon' => ['class' => 'icon-tcg']],
                ['title' => 'Actionfiguren', 'url' => '#', 'icon' => ['class' => 'icon-actionfigure']],
                ['title' => 'Kinderspiele', 'url' => '#', 'icon' => ['class' => 'icon-childgame']],
                ['title' => 'Kartenspiele', 'url' => '#', 'icon' => ['class' => 'icon-cardgame']],
                ['title' => 'W??rfelspiele', 'url' => '#', 'icon' => ['class' => 'icon-dicegame']],
                ['title' => 'Partyspiele', 'url' => '#', 'icon' => ['class' => 'icon-partygame']],
                ['title' => 'Reisespiele', 'url' => '#', 'icon' => ['class' => 'icon-travelgame']],
                ['title' => 'Abstrakte Spiele', 'url' => '#', 'icon' => ['class' => 'icon-abstractgame']],
                ['title' => 'Spiel des Jahres', 'url' => '#', 'icon' => ['class' => 'icon-spiel-des-jahres']],
            ],
        ];

        return $data;
    }

    public function getDemoData_menu_horizontal() {
        $data = [
            'title' => 'Kategorien',
            'items' => [
                ['title' => 'Brettspiele', 'url' => '/test', 'icon' => ['class' => 'icon-boardgame', 'width' => '20', 'height' => '20']],
                ['title' => 'Puzzle', 'url' => '#', 'icon' => ['class' => 'icon-puzzle']],
                ['title' => 'Trading Cards', 'url' => '#', 'icon' => ['class' => 'icon-tcg']],
                ['title' => 'Actionfiguren', 'url' => '#', 'icon' => ['class' => 'icon-actionfigure']],
                ['title' => 'Kinderspiele', 'url' => '#', 'icon' => ['class' => 'icon-childgame']],
                ['title' => 'Kartenspiele', 'url' => '#', 'icon' => ['class' => 'icon-cardgame']],
                ['title' => 'W??rfelspiele', 'url' => '#', 'icon' => ['class' => 'icon-dicegame']],
                ['title' => 'Partyspiele', 'url' => '#', 'icon' => ['class' => 'icon-partygame']],
                ['title' => 'Reisespiele', 'url' => '#', 'icon' => ['class' => 'icon-travelgame']],
                ['title' => 'Abstrakte Spiele', 'url' => '#', 'icon' => ['class' => 'icon-abstractgame']],
                ['title' => 'Spiel des Jahres', 'url' => '#', 'icon' => ['class' => 'icon-spiel-des-jahres']],
            ],
        ];

        return $data;
    }

    // Flexbox
    public function getDemoData_flexbox_components() {

        // This can be seen as an example how a module would use components
        $list_compact_1 = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_LIST_COMPACT);
        $list_compact_2 = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_LIST_COMPACT);
        $list_compact_3 = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_LIST_COMPACT);


        // Render the whole component
        $data = [
            'nbr_columns' => 3,
            'elements' => [$list_compact_1, $list_compact_2, $list_compact_3]
        ];


        return $data;
    }

    // Tabs
    public function getDemoData_tab_components() {

        $carousel = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_CAROUSEL_COMPONENTS);
        $header = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_HEADER_DEFAULT);
        $imagecloud = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_IMAGECLOUD_DEFAULT);

        $demo_data = [
            ['title' => 'Carousel', 'content' => $carousel, 'display' => true],
            ['title' => 'Header', 'content' => $header],
            ['title' => 'Imagecloud', 'content' => $imagecloud],
        ];

        return $demo_data;
    }

}