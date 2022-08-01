<?php

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');

// Global Stuff
if (Tools::isSubmit('renderComponentWithAjax')) {
    $component = FrameworkController::getComponentByComponentName(Tools::getValue('component_name'));
    $data = json_decode(Tools::getValue('data'), true);
    $style = Tools::getValue('style');
    $component = FrameworkController::fetchElement($component, $data, [], $style);
    die(json_encode(['content' => $component]));
}

die(false);