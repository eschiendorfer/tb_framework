<?php

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/controllers/front/FrameworkController.php');

class tb_framework extends Module
{

	public $errors;

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
			!$this->registerHook('displayHeader')
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

        // Make sure that the default styles are always available */
        $this->context->controller->addCSS($this->_path.'/views/css/tb_framework.css');

        $this->context->smarty->assign([
            'css_selector' => FrameworkController::getAllCssSelectorsForElements(), // This allows usage of sth like: {$css_selector.button_primary} -> it's for module or core devs. As the theme designer know the selectors anyway
        ]);

	}

}