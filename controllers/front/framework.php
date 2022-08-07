<?php

class tb_frameworkFrameworkModuleFrontController extends ModuleFrontController {

    public $errors;

    public function __construct() {

        parent::__construct();

        // Disable left and right column
        $this->display_column_left = false;
        $this->display_column_right = false;

    }

    public function initContent() {

        parent::initContent();

        $type = pSQL(Tools::getValue('type'));
        $component = (bool)(Tools::getValue('component'));

        $this->context->smarty->assign(array(
            'meta_title' => 'TB FrontOffice Framework',
            'meta_description' => 'bla',
            'site_title' => 'TB Framework',
            'site_description' => 'Genzo is still workin on this.',
            'site_image' => '',
            'framework_content' => $type ? $this->renderFrameworkContentByType($type, $component) : '',
        ));

		$this->setTemplate('framework.tpl');
	}


    private function renderFrameworkContentByType($type, $component = false) {

        if ($component) {

            $reflectionClass = new ReflectionClass('FrameworkController');
            $components = [];

            foreach ($reflectionClass->getConstants() as $constant) {
                if (is_array($constant) && isset($constant['type']) && ($type==$constant['type'])) {
                    $components[] = [
                        'name' => $constant['name'],
                        'output' => FrameworkController::fetchElementDemo($constant),
                    ];
                }
            }

            $this->context->smarty->assign(array(
                'title' => ucwords($type),
                'components' => $components,
            ));
        }

        if (file_exists(_PS_MODULE_DIR_."tb_framework/views/templates/helper/{$type}.tpl")) {
            return $this->context->smarty->fetch(_PS_MODULE_DIR_."tb_framework/views/templates/helper/{$type}.tpl");
        }

        return $this->context->smarty->fetch(_PS_MODULE_DIR_."tb_framework/views/templates/helper/components.tpl");
    }

}