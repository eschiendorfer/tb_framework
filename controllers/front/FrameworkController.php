<?php

/**
 * Copyright (C) 2022 Emanuel Schiendorfer
 *
 * @author    Emanuel Schiendorfer <https://github.com/eschiendorfer>
 * @copyright 2022 Emanuel Schiendorfer
 */

require_once(dirname(__DIR__, 2).'/classes/FrameworkRegistry.php');

class FrameworkController extends FrontController {

    // Supported Components
    const COMPONENT_LIST_COMPACT = [
        'type' => 'list',
        'name' => 'list_compact',
    ];

    const COMPONENT_TABLE_DEFAULT = [
        'type' => 'table',
        'name' => 'table_default',
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

    const COMPONENT_IMAGECLOUD_AVATAR = [
        'type' => 'imagecloud',
        'name' => 'imagecloud_avatar',
    ];

    const COMPONENT_HEADER_DEFAULT = [
        'type' => 'header',
        'name' => 'header_default',
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

    const COMPONENT_CARD_PRODUCT_LIST = [
        'type' => 'card',
        'name' => 'card_product_list',
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

    const COMPONENT_MODAL_CONFIRMATION = [
        'type' => 'modal',
        'name' => 'modal_confirmation',
    ];

    const COMPONENT_FANCYBOX_DEFAULT = [
        'type' => 'fancybox',
        'name' => 'fancybox_default',
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

    const COMPONENT_ACCORDION_PREVIEW_BOX = [
        'type' => 'accordion',
        'name' => 'accordion_preview_box',
    ];

    // Progressbar
    const COMPONENT_PROGRESSBAR = [
        'type' => 'progressbar',
        'name' => 'progressbar_default',
    ];

    // Timeline
    const COMPONENT_TIMELINE = [
        'type' => 'timeline',
        'name' => 'timeline_default',
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

    // Rich Text Editor / WYSIWYG Editor
    const COMPONENT_RTE = [
        'type' => 'rte',
        'name' => 'rte_default'
    ];

    // Message
    const COMPONENT_MESSAGE_PREVIEW = [
        'type' => 'message',
        'name' => 'message_preview'
    ];
    const COMPONENT_MESSAGE_CHAT = [
        'type' => 'message',
        'name' => 'message_chat', // Only two people are involved
    ];

    const COMPONENT_MESSAGE_THREAD = [
        'type' => 'message',
        'name' => 'message_thread', // Many people could be involved
    ];

    // Calendar
    const COMPONENT_CALENDAR_COMPACT = [
        'type' => 'calendar',
        'name' => 'calendar_compact', // A compact calendar (monthly view)
    ];

    // Stats
    const COMPONENT_STAT_BAR_CHART_HORIZONTAL = [
        'type' => 'stats',
        'name' => 'bar_chart_horizontal',
    ];

    const COMPONENT_STAT_LINE_CHART = [
        'type' => 'stats',
        'name' => 'line_chart',
    ];

    // Pagination / showMore, showBefore

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

    // Fancybox Image
    const ELEMENT_FANCYBOX_IMAGE = [
        'name' => 'fancybox',
        'css_selector' => 'tbfw_fancybox',
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

    // Todo: Forms
    const ELEMENT_FORM_GROUP = [
        'name' => 'form_group',
        'css_selector' => 'tbfw_form_group',
    ];

    // Todo: Icons



    // Some other elements to be considered:
        // Close Button

    /* @var $module bool|tb_framework */
    public static $module = false;

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

    public static function getCssSelectorForElement($selector_element_default, $selector_size_default = '', $style = 'default') {
        trigger_error('FrameworkController::getCssSelectorForElement() is deprecated. Implement CSS selector resolution in ComponentDefinition::getCssSelector().', E_USER_DEPRECATED);
        return '';
    }


    public static function getAllCssSelectorsForElements() {
        trigger_error('FrameworkController::getAllCssSelectorsForElements() is deprecated. Use FrameworkRegistry::getAllCssSelectors() instead.', E_USER_DEPRECATED);
        return FrameworkRegistry::getAllCssSelectors();
    }

    public static function getComponentByComponentName($component_name) {
        $reflectionClass = new ReflectionClass(__CLASS__);
        $constants = $reflectionClass->getConstants();

        foreach ($constants as $constant_name => $constant_value) {
            if (str_contains($constant_name, 'COMPONENT_') && $constant_value['name']==$component_name) {
                return $constant_value;
            }
        }

        return false;
    }

    public static function getFilePathByComponent($component, $style, ComponentChannel $channel = ComponentChannel::WEB) {

        $type = $component['type'];
        $name = $component['name'];

        $suffix = $style ? '_'.$style : '';
        $file_path_relative = 'component/'.$type.'/'.$channel->value.'/'.$name.$suffix.'.tpl';

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
    public static function fetchElement($component, $data = [], $columns_rewrite = [], $style = '', $ajax = false, ComponentChannel $channel = ComponentChannel::WEB) {
        trigger_error('FrameworkController::fetchElement() is deprecated. Use ComponentDefinition::fetchWeb() instead.', E_USER_DEPRECATED);

        $componentInstance = null;

        if ($component instanceof ComponentDefinition) {
            $componentInstance = $component;
        }
        elseif (is_array($component) && isset($component['name'])) {
            $componentInstance = FrameworkRegistry::getByName($component['name']);
        }

        if (!$componentInstance) {
            throw new PrestaShopException('Component instance not found for fetchElement().');
        }

        if (!empty($columns_rewrite)) {
            trigger_error('FrameworkController::fetchElement() us of $columns_rewrite is deprecated. It should not be used at all.', E_USER_DEPRECATED);
            $data = self::replaceColumns($data, $columns_rewrite);
        }

        if ($channel === ComponentChannel::WEB) {
            return $componentInstance::fetchWeb($data, $style, $ajax);
        }

        if ($channel === ComponentChannel::EMAIL) {
            return $componentInstance::fetchEmail($data, $style, $ajax);
        }

        if ($channel === ComponentChannel::CSS_CLASSES) {
            return $componentInstance::fetchCssClasses($style);
        }

        throw new PrestaShopException('Unsupported channel for fetchElement().');
    }

    public static function fetchElementsAsArray($component, $datas = [], $columns_rewrite = [], $style = '', ComponentChannel $channel = ComponentChannel::WEB) {
        trigger_error('FrameworkController::fetchElementsAsArray() is deprecated. Build arrays locally and call fetchElement()/fetchWeb() per item.', E_USER_DEPRECATED);

        // This function is very helpful when building complex components like tabs, sliders and so on
        // It will return the content as an array, which can be put into another component afterwards
        $return_array = [];

        foreach ($datas as $data) {
            $return_array[] = self::fetchElement($component, $data, $columns_rewrite, $style, false, $channel);
        }

        return $return_array;
    }

    public static function fetchElementDemo($component, ComponentChannel $channel = ComponentChannel::WEB, string $style = '') {
        trigger_error('FrameworkController::fetchElementDemo() is deprecated. Use ComponentDefinition::fetchDemo() instead.', E_USER_DEPRECATED);

        if ($component instanceof ComponentDefinition) {
            return $component::fetchDemo($channel, $style);
        }

        if (is_array($component) && isset($component['name'])) {
            $componentInstance = FrameworkRegistry::getByName($component['name']);

            if ($componentInstance) {
                return $componentInstance::fetchDemo($channel, $style);
            }
        }

        throw new PrestaShopException('Demo data for component not found.');
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

                        // There are some strange egdecases where same names with different structure are involved
                        // Example blockcategories.tpl (items.link -> items.link.url) -> which means that link was a string and needs to become an array
                        if (isset($smarty_vars[$key_needed_structure[0]][$key_layer_1][$key_needed_structure[1]]) && !is_array(isset($smarty_vars[$key_needed_structure[0]][$key_layer_1][$key_needed_structure[1]]))) {
                            $smarty_vars[$key_needed_structure[0]][$key_layer_1][$key_needed_structure[1]] = [];
                        }

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

        // print_r($smarty_vars)

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


    // Helper
    public static function formatDateToTimeElapsed($datetime) {

        if (!static::$module) {
            static::$module = Module::getInstanceByName('tb_framework');
        }

        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        if ($diff->y) {
            $ago = sprintf(static::$module->l('%d years ago'), $diff->y);
        }
        elseif ($diff->m) {
            $ago = sprintf(static::$module->l('%d months ago'), $diff->m);
        }
        elseif ($diff->d) {
            $ago = sprintf(static::$module->l('%d days ago'), $diff->d);
        }
        elseif ($diff->h) {
            $ago = sprintf(static::$module->l('%d hours ago'), $diff->h);
        }
        elseif ($diff->i) {
            $ago = sprintf(static::$module->l('%d minutes ago'), $diff->i);
        }
        elseif ($diff->s) {
            $ago = sprintf(static::$module->l('%d seconds ago'), $diff->s);
        }
        else{
            $ago = static::$module->l('just now');
        }

        return $ago;

    }

    public static function removeAjaxLoginFromHtmlString($htmlString) {
        $htmlString = str_replace('data-ajax-login="true"', '', $htmlString);
        return str_replace("data-ajax-login='true'", '', $htmlString);
    }
}
