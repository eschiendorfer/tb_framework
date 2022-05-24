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

    const COMPONENT_LIST_COMPONENTS = [
        'type' => 'list',
        'name' => 'list_components',
    ];

    const COMPONENT_IMAGECLOUD_DEFAULT = [
        'type' => 'imagecloud',
        'name' => 'imagecloud_default',
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

    const COMPONENT_TAB_COMPONENTS = [
        'type' => 'tab',
        'name' => 'tab_components',
    ];

    const COMPONENT_MENU_VERTICAL = [
        'type' => 'menu',
        'name' => 'menu_vertical',
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
    const CSS_COLOR_PRIMARY = 'color_primary';
    const CSS_COLOR_SECONDARY = 'color_secondary';
    const CSS_COLOR_TERTIARY = 'color_tertiary';
    const CSS_COLOR_GRAY = 'color_gray';
    const CSS_COLOR_GRAY_LIGHT = 'color_gray_light';
    const CSS_COLOR_GRAY_DARK = 'color_gray_dark';
    const CSS_COLOR_BLACK = 'color_black'; // Often times a clean black is too hard...

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
    const ELEMENT_PRICE_DEFAULT = 'price_default';
    const ELEMENT_PRICE_REDUCED = 'price_reduced';
    const ELEMENT_PRICE_ORIGINAL = 'price_original';

        const ELEMENT_PRICE_SIZE_LARGE = 'price_size_large';
        const ELEMENT_PRICE_SIZE_SMALL = 'price_size_small';

    // Stock Information
    const ELEMENT_STOCK_AVAILABLE = 'stock_available';
    const ELEMENT_STOCK_NOT_AVAILABLE = 'stock_not_available';
    const ELEMENT_STOCK_PREORDER = 'stock_preorder';

    // Tags
    const ELEMENT_TAG_ON_SALE = 'tag_on_sale';
    const ELEMENT_TAG_NEW = 'tag_new';



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
        $this->smarty_vars = $demo ? $this->getDemoData() : $smarty_vars;

    }

    public static function getCssSelectorForElement($selector_element_default, $selector_size_default = '') {

        // Check if the css selector should be overridden due to any value in theme config.xml file
        $configObject = simplexml_load_file(_PS_THEME_DIR_.'config.xml');

        $selector_element = $selector_element_default['css_selector'];

        $selector_size = $selector_size_default['css_selector'];

        foreach ($configObject->custom_css_selectors->custom_css_selector as $custom_css_selector) {
            if (($custom_css_selector['name']==$selector_element_default['name']) && $custom_css_selector['custom']) {
                $selector_element = $custom_css_selector['custom'];
            }

            if (($custom_css_selector['name']==$selector_size_default['name']) && $custom_css_selector['custom']) {
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
    public function fetchElement() {

        // Main component
        $this->context->smarty->assign([
            // Todo: probably this css_selector should be in global front_controller
            'css_selector' => $this->getAllCssSelectorsForElements(), // This allows usage of sth like: {$css_selector.button_primary} -> it's for module or core devs. As the theme designer know the selectors anyway
            'component' => $this->smarty_vars,
            'first_call' => !isset(self::$alreadyCalled[$this->component['name']]),
        ]);

        if (isset($this->smarty_vars['id'])) {
            Media::addJsDef(['id' => $this->smarty_vars['id']]);
        }

        self::$alreadyCalled[$this->component['name']] = true;

        $component_tpl_file = self::getFilePathByComponent($this->component);

        return $this->context->smarty->fetch($component_tpl_file);
    }


    // Demo
    private function getDemoData() {

        $method_name = 'getDemoData_'.$this->component['name'];

        if (method_exists(__CLASS__, $method_name)) {
            return $this->{$method_name}();
        }
        else {
            throw new PrestaShopException("Demo Data Function {$method_name} not found!");
        }

    }

    public function getDemoData_List_Compact() {

        $query = new \DbQuery();
        $query->select('p.*, pl.*, cl.name AS category, m.name AS manufacturer');
        $query->from('product', 'p');
        $query->innerJoin('product_shop', 'ps', 'ps.id_product=p.id_product AND ps.id_shop='.$this->context->shop->id);
        $query->innerJoin('product_lang', 'pl', 'pl.id_product=p.id_product');
        $query->innerJoin('category_lang', 'cl', 'cl.id_category=p.id_category_default AND cl.id_lang='.$this->context->language->id);
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
                'img' => $this->context->link->getImageLink($product['link_rewrite'], Product::getCover($product['id_product'])['id_image'], 'small_default'),
                'title' => $product['name'],
                'subtitle' => $product['category'].$manufacturer,
                'link' => ['url' => $this->context->link->getProductLink($product['id_product'])],
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

    public function getDemoData_imagecloud_default() {

        // Todo: how to know how many elements are showed? -> the theme needs to tell
        $manufacturers = Manufacturer::getManufacturers(false, $this->context->language->id, true, 1, 12);

        $data = [];

        foreach ($manufacturers as $manufacturer) {
            $data[] = [
                'src' => __PS_BASE_URI__ . 'img/m/' . (int) $manufacturer['id_manufacturer'] . '.jpg',
                'title' => $manufacturer['name'],
                'link' => ['url' => $this->context->link->getManufacturerLink($manufacturer['id_manufacturer'])],
            ];
        }


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


    public function getDemoData_header_default() {

        $demo_data = [
            'title' => 'Die Meisten sind richtig gut',
            'subtitle' => 'Marken & Verlage',
            'description' => 'The market has like one million brands. 99% of them don\'t fit our expectation. Only the best one will be listed on our site...',
        ];

        return $demo_data;
    }

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
            'description' => 'Eltern können ihren Kindern nichts Schöneres schenken als gemeinsame Zeit. Brettspiele schweissen zusammen und man erlebt lustige Momente, an die man sich noch Jahre später erinnert.',
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
            'title' => 'Sozialer als alle sozialen Netzwerke',
            'subtitle' => 'Familienspiele',
            'description' => 'Eltern können ihren Kindern nichts Schöneres schenken als gemeinsame Zeit. Brettspiele schweissen zusammen und man erlebt lustige Momente, an die man sich noch Jahre später erinnert. Für einen Spielnachmittag reichen ein paar Snacks, leckere Getränke und ein tolles Spiel aus.',
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
            'subtitle' => 'Familienspiele',
            'image' => [
                'src' => '/themes/genzo_theme/img/cover.png',
            ],
            'link' => '/card_simple/demo',
        ];

        return $demo_data;
    }

    public function getDemoData_card_product() {

        $demo_data = [
            'title' => 'Sozialer als alle sozialen Netzwerke',
            'subtitle' => 'Familienspiele',
            'image' => [
                'src' => '/themes/genzo_theme/img/cover.png',
            ],
            'link' => '/card_simple/demo',
        ];

        return $demo_data;
    }


    public function getDemoData_modal_default() {

        $html = '<b>Genzo is King</b>';

        $demo_data = [
            'show' => false, // Should the modal open on page load?
            'close_button' => true, // Should there be a close button on top right?
            'close_background' => true, // Should the modal close, when the user clicks on the background outside the modal?
            'id' => 'modal_unique_id', // Make sure that you chose-something unique
            'html' => $html,
        ];

        return $demo_data;
    }

    public function getDemoData_carousel_components() {

        $demo_data = [
            'lists' => [], // Fill an array with components
        ];

        return $demo_data;
    }




}