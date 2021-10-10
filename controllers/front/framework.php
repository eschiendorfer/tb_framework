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

        // Make Sure that CSS Selectors are available
        $this->context->smarty->assign(array(
            'css_selector' => FrameworkController::getAllCssSelectorsForElements(),
        ));

        $type = pSQL(Tools::getValue('type'));
        $component = (bool)(Tools::getValue('component'));

        $this->context->smarty->assign(array(
            'meta_title' => 'TB FrontOffice Framework',
            'meta_description' => 'bla',
            'framework_content' => $type ? $this->renderFrameworkContentByType($type, $component) : '',
        ));

		$this->setTemplate('framework.tpl');
	}


    private function renderFrameworkContentByType($type, $component = false) {

        if ($component) {
            $this->context->smarty->assign(array(
                'components' => $this->{'render'.$type.'Components'}(),
            ));
        }

        return $this->context->smarty->fetch(_PS_MODULE_DIR_."tb_framework/views/templates/helper/{$type}.tpl");
    }

    private function renderCardsComponents() {

        $components_output = [];

        $components = [
            FrameworkController::COMPONENT_CARD_PROMO,
            FrameworkController::COMPONENT_CARD_DEFAULT,
            FrameworkController::COMPONENT_CARD_SIMPLE,
        ];

        foreach ($components as $component) {
            $frameworkComponent = new FrameworkController($component, [], true);
            $components_output[] = [
                'name' => $component['name'],
                'output' => $frameworkComponent->fetchElement(),
            ];
        }

        return $components_output;
    }

}