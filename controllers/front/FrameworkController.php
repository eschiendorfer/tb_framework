<?php

/**
 * Copyright (C) 2022 Emanuel Schiendorfer
 *
 * @author    Emanuel Schiendorfer <https://github.com/eschiendorfer>
 * @copyright 2022 Emanuel Schiendorfer
 */


class FrameworkController extends FrontController {

    // Supported Components
    const COMPONENT_LIST_COMPACT = [
        'type' => 'list',
        'name' => 'list_compact',
    ]; // Todo: Are tables the same as lists?

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

    const COMPONENT_MODAL_LOGIN = [
        'type' => 'modal',
        'name' => 'modal_login',
    ];

    const COMPONENT_MODAL_ADD_TO_CART = [
        'type' => 'modal',
        'name' => 'modal_add_to_cart',
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

    const COMPONENT_ACCORDION = [
        'type' => 'accordion',
        'name' => 'accordion_default',
    ];

    // Progressbar
    const COMPONENT_PROGRESSBAR = [
        'type' => 'progressbar',
        'name' => 'progressbar_default',
    ];

    // Reviews
    const COMPONENT_REVIEW_GRADE = [
        'type' => 'review',
        'name' => 'review_grade',
    ];

    const COMPONENT_REVIEW = [
        'type' => 'review',
        'name' => 'review_default',
    ];

    const COMPONENT_REVIEW_STATS = [
        'type' => 'review',
        'name' => 'review_stats',
    ];

    const COMPONENT_REVIEW_SECTION = [
        'type' => 'review',
        'name' => 'review_section',
    ];

    // Dropdown / Popover / Tooltip
    const COMPONENT_POPOVER = [
        'type' => 'popover',
        'name' => 'popover_default',
    ];

    // Toast
    const COMPONENT_TOAST = [
        'type' => 'toast',
        'name' => 'toast',
    ];

    // Pagination / showMore, showBefore
    // Comments (UI Kit/Fomantic)
    // Check the use of section (UI Kit)

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

    // Todo: Find a solution for boxes and margins / sections
    // Todo: Same is true for icons. Keep in mind that size and color is important too

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
        'css_selector' => 'tbfw_spinner_default', // 100% width & height of parent element, border can be set manually on div
    ];


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

    // Forms
    const ELEMENT_FORM_GROUP = [
        'name' => 'form_group',
        'css_selector' => 'tbfw_form_group',
    ];



    // Some other elements to be considered:
        // Close Button

    public static $alreadyCalledType = [];
    public static $alreadyCalledComponent = [];

    public static $ids_unique = [];

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
                $selector_element = (string)$custom_css_selector['custom'];
            }

            if ($selector_size_default && ($custom_css_selector['name']==$selector_size_default['name']) && $custom_css_selector['custom']) {
                $selector_size = (string)$custom_css_selector['custom'];
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

    public static function getComponentByComponentName($component_name) {
        $reflectionClass = new ReflectionClass(__CLASS__);
        $constants = $reflectionClass->getConstants();

        foreach ($constants as $constant_name => $constant_value) {
            if (strpos($constant_name, 'COMPONENT_')!==false && $constant_value['name']==$component_name) {
                return $constant_value;
            }
        }

        return false;
    }

    // Todo: consider adding components with subclasses / Interface -> validate function is very important

    public static function getFilePathByComponent($component, $style) {

        $type = $component['type'];
        $name = $component['name'];

        if ($style) {
            $file_path_relative = 'component/'.$type.'/'.$name.'/'.$name.'_'.$style.'.tpl';
        }
        else {
            $file_path_relative = 'component/'.$type.'/'.$name.'.tpl';
        }

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
    public static function fetchElement($component, $data = [], $columns_rewrite = [], $style = '', $ajax = false) {

        $context = Context::getContext();

        if (!empty($columns_rewrite)) {
            $data = self::replaceColumns($data, $columns_rewrite);
        }

        // Validate Data
        $method_name = 'validate_'.$component['name'];

        if (method_exists(__CLASS__, $method_name)) {
            self::{$method_name}($data);
        }

        self::setUniqueId($data, $component['name']);
        $data['component_name'] = $component['name'];

        // Main component
        $context->smarty->assign([
            'component' => $data,
            'first_call' => !isset(self::$alreadyCalledComponent[$component['name'].$style]),
            'first_call_type' => !isset(self::$alreadyCalledType[$component['type']]),
        ]);

        // We only want to assign the css_selector once
        if (!isset($context->smarty->tpl_vars['css_selector'])) {
            $context->smarty->assign('css_selector', self::getAllCssSelectorsForElements()); // Usage: {$css_selector.button_primary} -> it's for module or core devs. As the theme designer know the selectors anyway
        }

        self::$alreadyCalledComponent[$component['name']] = true;
        self::$alreadyCalledType[$component['type']] = true;

        $component_tpl_file = self::getFilePathByComponent($component, $style);

        $htmlElement = $context->smarty->fetch($component_tpl_file);

        if ($ajax) {
            return [
                'id' => $data['id'],
                'htmlElement' => $htmlElement,
            ];
        }


        return $htmlElement;
    }

    public static function fetchElementsAsArray($component, $datas = [], $columns_rewrite = [], $style = '') {

        // This function is very helpful when building complex components like tabs, sliders and so on
        // It will return the content as an array, which can be put into another component afterwards
        $return_array = [];

        foreach ($datas as $data) {
            $return_array[] = self::fetchElement($component, $data, $columns_rewrite, $style);
        }

        return $return_array;
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

    // Validate Functions
    private static function setUniqueId(&$data, $component_name) {

        if (!isset($data['id']) || !$data['id']) {
            do {
                // Having a combo and random makes it impossible to have two identical values even when ajax is used
                $unique_id = $component_name.'_'.time().'_'.rand(10000,99999);
            } while (in_array($unique_id, self::$ids_unique));

            $data['id'] = $unique_id;
        }

        // Adding unique id to global array
        self::$ids_unique[] = $data['id'];
    }

    public static function getLastGeneratedUniqueId() {
        return self::$ids_unique[array_key_last(self::$ids_unique)];
    }

    private static function validate_modal_default(&$data) {

        $data['width'] = empty($data['width']) ? 'medium' : pSQL($data['width']);
        $data['height'] = empty($data['height']) ? 'auto' : pSQL($data['height']);



        if (empty($data['triggers_show']) || !is_array($data['triggers_show'])) {
            $data['triggers_show'] = ['auto_show', 'click_item'];
        }

        if (empty($data['triggers_close']) || !is_array($data['triggers_close'])) {
            $data['triggers_close'] = ['click_close_button', 'click_item', 'click_outside'];
        }


        // Todo: check how we can make this 100% save
        /*if (!Validate::isCleanHtml($data['html'])) {
            $data['html'] = 'No clean html ...';
        }*/

    }

    private static function validate_modal_add_to_cart(&$data) {
        self::validate_modal_default($data);
    }

    private static function validate_modal_login(&$data) {
        self::validate_modal_default($data);
    }

    private static function validate_popover_default(&$data) {

        if (empty($data['item'])) {
            die('popover item is empty');
        }

        if (empty($data['popover_content'])) {
            die('popover content is empty');
        }

        if (empty($data['triggers_show']) || !is_array($data['triggers_show'])) {
            $data['triggers_show'] = ['click_item'];
        }

        if (empty($data['triggers_close']) || !is_array($data['triggers_close'])) {
            $data['triggers_close'] = ['click_item', 'click_outside', 'open_other_item'];
        }

        if (empty($data['position'])) {
            $data['position'] = 'bottom_center';
        }

        if (empty($data['zIndex'])) {
            $data['zIndex'] = 'default';
        }

        if (empty($data['margin'])) {
            $data['margin'] = 'default';
        }
    }

    private static function validate_carousel_components(&$data) {
        if ($data['nbr_columns'] > count($data['slides'])) {
            $data['nbr_columns'] = count($data['slides']);
        }
    }

    private static function validate_tab_components(&$data) {

        // Todo: this is just a quick usage for product page. A clean implementation with just one hook, would be nice
        if (!empty($data['hook'])) {
            $displayProductTabs = Hook::exec('displayProductTab', [], null, true);
            $displayProductsTabContents = Hook::exec('displayProductTabContent', [], null, true);

            foreach ($displayProductTabs as $module => $displayProductTab) {
                $data['tabs'][] = [
                    'title' => $displayProductTab,
                    'content' => $displayProductsTabContents[$module],
                ];
            }
        }

        foreach ($data['tabs'] as $key => $tab) {
            if (empty($tab['title']) || empty($tab['content'])) {
                unset($data['tabs'][$key]);
            }
        }
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

    public static function getDemoData_List_compact() {

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
    public static function getDemoData_imagecloud_default() {

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

    public static function getDemoData_imagecloud_promo() {
        $demo_data = [
            'header' => [
                'title' => 'Klassiker gehen immer oder nicht!?',
                'subtitle' => 'Unsere Lieblingsstücke',
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
    public static function getDemoData_header_default() {

        $demo_data = [
            'title' => 'Die Meisten sind richtig gut',
            'subtitle' => 'Marken & Verlage',
            'description' => 'The market has like one million brands. 99% of them don\'t fit our expectation. Only the best one will be listed on our site...',
        ];

        return $demo_data;
    }

    // Feature
    public static function getDemoData_feature_default() {

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
    public static function getDemoData_card_default() {

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

    public static function getDemoData_card_promo() {

        $demo_data = [
            'title' => 'Sozialer als soziale Netzwerke',
            'subtitle' => 'Familienspiele',
            'description' => 'Eltern können Kindern nichts Schöneres schenken als gemeinsame Zeit. Mit Brettspielen erlebt man lustige Momente, an die man sich noch Jahre später erinnert. Für einen Spielnachmittag reichen ein paar Snacks, leckere Getränke und ein tolles Spiel.',
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

    public static function getDemoData_card_simple() {

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

    public static function getDemoData_card_product() {

        $products = Product::getProducts(1, 1, 1, 'id_product', 'ASC');
        $product = $products[0];

        $product['id_image'] = Product::getCover($product['id_product'])['id_image'];

        $product = Product::getProductProperties(1, $product);

        return $product;
    }

    // Modal
    public static function getDemoData_modal_default() {

        $html = '<br><br>This is a custom modal';

        $html .= FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_HEADER_DEFAULT);
        $html .= FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_CAROUSEL_COMPONENTS);

        $demo_data = [
            'html' => $html, // Required
            'title' => 'Custom Modal',
            'width' => 'medium', // Possible values: full, big, medium, small & any custom css_width_value (example: 80vw)
            'height' => 'auto', // Possible values: full, big, medium, small & any custom css_width_value (example: 90vh)
            'item' => '',
            'triggers_show' => ['auto_show', 'click_item'], // Possible values: 'auto_show', 'click_item'
            'triggers_close' => ['click_close_button', 'click_item', 'click_outside'], // Possible value 'click_close_button', 'click_item', 'click_outside'
        ];

        return $demo_data;
    }

    public static function getDemoData_modal_add_to_cart() {

        // Random Product
        $products = Product::getProducts(1, 1, 1, 'id_product', 'ASC');
        $product = $products[0];
        $product['id_image'] = Product::getCover($product['id_product'])['id_image'];
        $product = Product::getProductProperties(1, $product);

        // Cart Summary
        $cart_summary = [

        ];

        $demo_data = [
            'product' => $product, // Required
            'cart_summary' => $cart_summary,
            'title' => 'Custom Modal',
            'width' => 'medium', // Possible values: full, big, medium, small & any custom css_width_value (example: 80vw)
            'height' => 'auto', // Possible values: full, big, medium, small & any custom css_width_value (example: 90vh)
        ];

        return $demo_data;
    }


    public static function getDemoData_modal_login() {

        $demo_data = [
            'width' => 'small', // Possible values: full, big, medium, small & any custom css_width_value (example: 80vw)
            'height' => 'auto', // Possible values: full, big, medium, small & any custom css_width_value (example: 90vh)
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
            $product = Product::getProductProperties(1, $product);
            $productBoxes[] = FrameworkController::fetchElement(FrameworkController::COMPONENT_CARD_PRODUCT, $product);
        }

        // Render the whole component
        $demo_data = [
            'nbr_columns' => 3.5,
            'slides' => $productBoxes
        ];

        return $demo_data;
    }

    public static function getDemoData_carousel_promo() {

        $demo_data_carousel = self::getDemoData_carousel_components();

        $demo_data_carousel['nbr_columns'] = 2.5;
        $demo_data_carousel['promo_position'] = 'left';

        // Todo: probably we should add a boolean value for "boxed". This could actually be true for all containers
        // Maybe we can fix it also, if components need to implement a header and a show_all variant.

        return $demo_data_carousel;
    }

    // Menus
    public static function getDemoData_menu_vertical() {
        $data = [
            'title' => 'Kategorien',
            'items' => [
                ['title' => 'Brettspiele', 'url' => '/test', 'icon' => ['class' => 'icon-boardgame', 'width' => '20', 'height' => '20']],
                ['title' => 'Puzzle', 'url' => '#', 'icon' => ['class' => 'icon-puzzle']],
                ['title' => 'Trading Cards', 'url' => '#', 'icon' => ['class' => 'icon-tcg']],
                ['title' => 'Actionfiguren', 'url' => '#', 'icon' => ['class' => 'icon-actionfigure']],
                ['title' => 'Kinderspiele', 'url' => '#', 'icon' => ['class' => 'icon-childgame']],
                ['title' => 'Kartenspiele', 'url' => '#', 'icon' => ['class' => 'icon-cardgame']],
                ['title' => 'Würfelspiele', 'url' => '#', 'icon' => ['class' => 'icon-dicegame']],
                ['title' => 'Partyspiele', 'url' => '#', 'icon' => ['class' => 'icon-partygame']],
                ['title' => 'Reisespiele', 'url' => '#', 'icon' => ['class' => 'icon-travelgame']],
                ['title' => 'Abstrakte Spiele', 'url' => '#', 'icon' => ['class' => 'icon-abstractgame']],
                ['title' => 'Spiel des Jahres', 'url' => '#', 'icon' => ['class' => 'icon-spiel-des-jahres']],
            ],
        ];

        return $data;
    }

    public static function getDemoData_menu_horizontal() {
        $data = [
            'title' => 'Kategorien',
            'items' => [
                ['title' => 'Brettspiele', 'url' => '/test', 'icon' => ['class' => 'icon-boardgame', 'width' => '20', 'height' => '20']],
                ['title' => 'Puzzle', 'url' => '#', 'icon' => ['class' => 'icon-puzzle']],
                ['title' => 'Trading Cards', 'url' => '#', 'icon' => ['class' => 'icon-tcg']],
                ['title' => 'Actionfiguren', 'url' => '#', 'icon' => ['class' => 'icon-actionfigure']],
                ['title' => 'Kinderspiele', 'url' => '#', 'icon' => ['class' => 'icon-childgame']],
                ['title' => 'Kartenspiele', 'url' => '#', 'icon' => ['class' => 'icon-cardgame']],
                ['title' => 'Würfelspiele', 'url' => '#', 'icon' => ['class' => 'icon-dicegame']],
                ['title' => 'Partyspiele', 'url' => '#', 'icon' => ['class' => 'icon-partygame']],
                ['title' => 'Reisespiele', 'url' => '#', 'icon' => ['class' => 'icon-travelgame']],
                ['title' => 'Abstrakte Spiele', 'url' => '#', 'icon' => ['class' => 'icon-abstractgame']],
                ['title' => 'Spiel des Jahres', 'url' => '#', 'icon' => ['class' => 'icon-spiel-des-jahres']],
            ],
        ];

        return $data;
    }

    // Flexbox
    public static function getDemoData_flexbox_components() {

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
    public static function getDemoData_tab_components() {

        $carousel = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_CAROUSEL_COMPONENTS);
        $header = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_HEADER_DEFAULT);
        $imagecloud = FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_IMAGECLOUD_DEFAULT);

        $demo_data = [
            'tabs' => [
                ['title' => 'Carousel', 'content' => $carousel, 'display' => true],
                ['title' => 'Header', 'content' => $header],
                ['title' => 'Imagecloud', 'content' => $imagecloud],
            ]
        ];

        return $demo_data;
    }

    // Accordion
    public static function getDemoData_accordion_default() {


        $items = [
            'sections' => [
                [
                    'title' => 'Puzzles',
                    'items' => [
                        [
                            'title' => 'Können Einzelteile nachbestellt werden?',
                            'content' => 'aksdjfkjsdkf',
                        ],
                    ]
                ],
                [
                    'title' => 'Allgemein',
                    'items' => [
                        [
                            'title' => 'Wie schnell ist der Versand?',
                            'content' => 'aksdjfkjsdkf',
                        ],
                        [
                            'title' => 'Kann ich auf Rechnung kaufen?',
                            'content' => 'aksdjfkjsdkf',
                        ],
                    ]
                ],
            ],
        ];

        return $items;
    }

    // Popover
    public static function getDemoData_popover_default() {

        /* Possible positions:
            'bottom_left', 'bottom_center', 'bottom_right',
            'top_left', 'top_center', 'top_right',
            'left_top', left_center', 'left_bottom',
            'right_top', right_center', 'right_bottom',
        */

        $popover = [
            'item' => 'Hover me', // required
            'popover_content' => FrameworkController::fetchElementDemo(FrameworkController::COMPONENT_CARD_PRODUCT), // required
            'triggers_show' => ['click_item'], // Possible values: 'click_item', 'mouseenter_item'
            'triggers_close' => ['click_item', 'click_outside', 'open_other_item'], // Possible value 'click_item', 'mouseleave_item', 'click_outside', 'open_other_item'
            'position' => 'bottom_center',
            'zIndex' => 'default', // Possible values: default, high, max
            'margin' => 10, // Possible values: default, high, max
        ];

        return $popover;
    }

    // Toast
    public static function getDemoData_toast() {

        $toast = [
            'html' => '<div>Ich bin ein Toast</div>', // required
            'hide_after' => 5000, // in ms
        ];

        return $toast;
    }

    // Progressbar
    public static function getDemoData_progressbar_default() {
        $progessbar = [
            'progress_percentage' => rand(5,100),
        ];

        return $progessbar;
    }

    // Reviews
    public static function getDemoData_review_grade() {

        $review_stats = [
            'review_grade' => rand(1*10,5*10)/10,
        ];

        return $review_stats;
    }

    public static function getDemoData_review_default($key = null) {

        $reviews_default[] = [
            'customer' => [
                'name' => 'Genzo Wakabayashi',
                'image' => ['src' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80'],
                'link' => ['url'],
            ],
            'review_grade' => rand(1*10,5*10)/10,
            'review_date' => date('d. F Y'),
            'review_title' => 'Something',
            'review_content' => '<p>Mauris non odio at est convallis rhoncus at vitae odio. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut sagittis, nibh sit amet porttitor efficitur, purus urna elementum dolor, in congue ipsum augue scelerisque ex.</p>',
        ];

        $reviews_default[] = [
            'customer' => [
                'name' => 'Martina Meyer',
                'image' => ['src' => 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80'],
                'link' => ['url'],
            ],
            'review_grade' => rand(1*10,5*10)/10,
            'review_date' => date('d. F Y', strtotime('-3 months -2 days')),
            'review_title' => 'Something',
            'review_content' => '<p>Nullam placerat luctus odio, sed tincidunt ex volutpat sed. Maecenas at magna nec mi vulputate egestas eget non nibh. </p>',
        ];

        $reviews_default[] = [
            'customer' => [
                'name' => 'Sadio Perreira',
                'image' => ['src' => 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixqx=oilqXxSqey&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'],
                'link' => ['url'],
            ],
            'review_grade' => rand(1*10,5*10)/10,
            'review_date' => date('d. F Y', strtotime('-1 year -2 weeks')),
            'review_title' => 'Something',
            'review_content' => '<p>In hac habitasse platea dictumst. Nunc volutpat neque vitae nunc condimentum, placerat elementum ex gravida. In eu ligula sodales, egestas nunc id, porttitor lorem. Vestibulum pretium risus eu turpis bibendum vehicula. Morbi vestibulum tellus non tortor molestie, sit amet maximus leo mattis.</p><p>Morbi facilisis ipsum quis odio efficitur egestas sit amet ac quam. Fusce sodales ex sem. Nunc at sapien auctor, dapibus ipsum at, varius purus. Aenean egestas enim in lorem porttitor pulvinar. Quisque suscipit lobortis enim vitae rutrum. Quisque a neque dolor. Curabitur non sodales lectus.</p>',
        ];

        if (is_null($key)) {
            $key = rand(1, count($reviews_default)) - 1;
        }

        return $reviews_default[$key];
    }

    public static function getDemoData_review_stats() {

        $stats = [
            5 => rand(0,100),
            4 => rand(0,200),
            3 => rand(0,50),
            2 => rand(0,50),
            1 => rand(0,100),
        ];

        $reviews_count_total = array_sum($stats);

        $stars_total = 0;

        foreach ($stats as $star => $count) {
            $stars_total+= $star*$count;
        }

        $review_stats = [
            'reviews_grade_aggregated' => round($stars_total/$reviews_count_total,2),
            'reviews_total_count' => $reviews_count_total,
            'stats' => $stats
        ];

        return $review_stats;
    }

    public static function getDemoData_review_section() {

        $review_stats = self::getDemoData_review_stats();

        $review_section = [
            'reviews_grade_aggregated' => $review_stats['reviews_grade_aggregated'],
            'reviews_total_count' => $review_stats['reviews_total_count'],
            'stats' => $review_stats['stats'],
            'write_button_content' => '',
            'reviews' => [
                self::getDemoData_review_default(0),
                self::getDemoData_review_default(1),
                self::getDemoData_review_default(2),
            ],
            'reviews_pagination_content' => '',
        ];

        return $review_section;
    }
}