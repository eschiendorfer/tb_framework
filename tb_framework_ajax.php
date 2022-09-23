<?php

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');

// Global Stuff
if (Tools::isSubmit('renderComponentWithAjax')) {
    $component = FrameworkController::getComponentByComponentName(Tools::getValue('component_name'));
    $data = json_decode(Tools::getValue('data'), true);
    $style = Tools::getValue('style');
    $component = FrameworkController::fetchElement($component, $data, [], $style);
    die(json_encode(['id' => FrameworkController::getLastGeneratedUniqueId(), 'content' => $component]));
}

if (Tools::isSubmit('getProductPrice')) {
    $id_product = (int)Tools::getValue('id_product');
    $id_product_attribute = (int)Tools::getValue('id_product_attribute');
    $qty = (int)Tools::getValue('qty', 1);
    $use_tax = (bool)Tools::getValue('use_tax', true);

    $price = Product::getPriceStatic($id_product, $use_tax, $id_product_attribute, _TB_PRICE_DATABASE_PRECISION_, null, false, true, $qty);

    if (Tools::getValue('format_price')) {
        $price = Tools::displayPrice($price);
    }

    return die(json_encode($price));
}

die(false);