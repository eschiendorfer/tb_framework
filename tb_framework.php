<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/controllers/front/FrameworkController.php');

class tb_framework extends Module
{

	public $errors;
    private $tabs;

	function __construct() {
		$this->name = 'tb_framework';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'Emanuel Schiendorfer';
		$this->need_instance = 0;

		$this->bootstrap = true;

        $this->controllers = array('framework');

	 	parent::__construct();

		$this->displayName = $this->l('ThirtyBees FO Framework');
		$this->description = $this->l('Render FO Elements/Components easily with this framework.');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

	}

	public function install() {
		if (!parent::install() OR
			!$this->registerHook('moduleRoutes') OR
			!$this->registerHook('displayHeader') OR
			!$this->registerHook('displayTab') OR
			!$this->registerHook('displayBottomColumn')
        ) {
            return false;
        }

		return true;
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

	public function hookDisplayHeader($params) {

        // Make Sure that CSS Selectors are always available
        $this->context->smarty->assign(array(
            'css_selector' => FrameworkController::getAllCssSelectorsForElements(),
        ));

        Media::addJsDef(array(
            'css_selector' => FrameworkController::getAllCssSelectorsForElements(),
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
            return FrameworkController::fetchElement(FrameworkController::COMPONENT_TAB_COMPONENTS, $this->tabs[$id]);
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

    // Compatibility
    private function fakeTranslations() {
        // Note: we don't use ModuleFrontController in FrameworkController
        // This seems to make problems for translations if they are loaded by static methods
        // That's why we call here each string from FrameworkController::formatDateToTimeElapsed()
        $this->l('%d years ago');
        $this->l('%d months ago');
        $this->l('%d days ago');
        $this->l('%d hours ago');
        $this->l('%d minutes ago');
        $this->l('%d seconds ago');
        $this->l('just now');
    }

}