<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/autoload.php');

class tb_framework extends Module
{
    private const SHORTCODE_REGISTRATION_HOOK = 'actionGenzoShortcodesRegister';

	public $errors;
    private $tabs;

	function __construct() {
		$this->name = 'tb_framework';
		$this->tab = 'front_office_features';
		$this->version = '1.0.1';
		$this->author = 'Emanuel Schiendorfer';
		$this->need_instance = 0;

		$this->bootstrap = true;

        $this->controllers = array('framework', 'ajaxlist');

	 	parent::__construct();

		$this->displayName = $this->l('ThirtyBees FO Framework');
		$this->description = $this->l('Render FO Elements/Components easily with this framework.');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

	}

	public function install() {
		if (!parent::install() OR
			!$this->registerHook('moduleRoutes') OR
			!$this->registerHook('actionRegisterAutoloader') OR
            !$this->registerShortcodeRegistrationHook() OR
			!$this->registerHook('displayHeader') OR
			!$this->registerHook('displayTab') OR
			!$this->registerHook('displayBottomColumn')
        ) {
            return false;
        }

		return true;
	}

    private function registerShortcodeRegistrationHook(): bool
    {
        if ((int)Hook::getIdByName(self::SHORTCODE_REGISTRATION_HOOK) <= 0) {
            $hook = new Hook();
            $hook->name = self::SHORTCODE_REGISTRATION_HOOK;
            $hook->title = 'Genzo Shortcodes Registration';
            $hook->description = 'Allows modules to register shortcode handler classes.';
            $hook->position = false;
            $hook->live_edit = false;

            if (!(bool)$hook->add()) {
                return false;
            }
        }

        return $this->registerHook(self::SHORTCODE_REGISTRATION_HOOK);
    }

	public function uninstall() {
		if (!parent::uninstall()) {
            return false;
        }

		return true;
	}


	
	// Backoffice 
	public function getContent() {
        $link = $this->context->link->getModuleLink('tb_framework', 'framework');
        return "<div>Watch the Elements & Componets: <a href='{$link}' target='_blank'>{$link}</a></div>";
	}

	
	// Hooks
    public function hookModuleRoutes() {

        $my_routes = array(
            'module-tb_framework-framework' => array(
                'controller' => 'framework',
                'rule' => 'tb-framework',
                'keywords' => array(
                    'link_rewrite' => array(
                        'regexp' => '[_a-zA-Z0-9-\pL]*',
                        'param' => 'link_rewrite',
                    )
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'tb_framework',
                    'controller' => 'framework',
                ),
            ),
        );

        return $my_routes;
    }

    public function hookActionRegisterAutoloader() {
        require_once(dirname(__FILE__).'/autoload.php');
        FrameworkRegistry::assignCssSelectorsToSmarty();
    }

    public function hookActionGenzoShortcodesRegister($params)
    {
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/BoxShortcode.php';
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/ButtonShortcode.php';
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/CarouselShortcode.php';
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/CardShortcode.php';
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/HeaderShortcode.php';
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/ImagecloudcompactShortcode.php';
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/ListShortcode.php';
        require_once _PS_MODULE_DIR_ . 'tb_framework/classes/shortcodes/ProductgridShortcode.php';

        return [
            \BoxShortcode::class,
            \ButtonShortcode::class,
            \CarouselShortcode::class,
            \CardShortcode::class,
            \HeaderShortcode::class,
            \ImagecloudcompactShortcode::class,
            \ListShortcode::class,
            \ProductgridShortcode::class,
        ];
    }

	public function hookDisplayHeader($params) {
        FrameworkRegistry::assignCssSelectorsToSmarty();

        Media::addJsDef(array(
            'css_selector' => FrameworkRegistry::getAllCssSelectors(),
        ));

        // Make sure that the default styles are always available */
        $this->context->controller->addCSS($this->_path.'views/css/tb_framework.css');

        // Make components available by ajax
        $this->context->controller->addJS($this->_path.'views/js/tb_framework.js');


        // Tabs Hooks
        $tabs = Hook::exec('displayTabContent', [], '', true);

        foreach ($tabs as $module => $hooks) {
            if (is_array($hooks) && !empty($hooks)) {
                foreach ($hooks as $hook => $tabs) {
                    // Keep SEO and community content on the main listing page to avoid duplicate pagination content.
                    if ($hook === 'displayBottomColumn' && (int)Tools::getValue('offset') > 0) {
                        continue;
                    }

                    // Todo: Do a validation before and make a clean structure
                    foreach ($tabs as $tab) {
                        $this->tabs[$hook]['tabs'][] = $tab;
                    }
                }
            }
        }

	}

    public function hookDisplayTab($params) {
        $id = pSQL($params['id']);
        if (!empty($this->tabs[$id])) {
            return TabComponentsComponent::fetchWeb($this->tabs[$id]);
        }

        return null;
    }



    // Display Hooks that support tabs functionality
    public function hookDisplayBottomColumn($params) {

        if (!empty($this->tabs['displayBottomColumn'])) {
            return $this->hookDisplayTab(['id' => 'displayBottomColumn']);
        }

        return null;

    }

    /**
     * Used by the Back Office translation scanner. Runtime translation happens in FrameworkDateFormatter.
     */
    private function registerStaticFormatterTranslations() {
        $this->l('%d years ago', 'FrameworkDateFormatter');
        $this->l('%d months ago', 'FrameworkDateFormatter');
        $this->l('%d days ago', 'FrameworkDateFormatter');
        $this->l('%d hours ago', 'FrameworkDateFormatter');
        $this->l('%d minutes ago', 'FrameworkDateFormatter');
        $this->l('%d seconds ago', 'FrameworkDateFormatter');
        $this->l('just now', 'FrameworkDateFormatter');
    }

}
