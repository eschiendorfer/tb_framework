<?php

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/autoload.php');

// Global Stuff
if (Tools::isSubmit('renderComponentWithAjax') && ($componentName = pSQL(Tools::getValue('component_name')))) {

    if (!Validate::isMailName($componentName)) {
        die(false); // Likely a hacking attempt
    }

    $component = FrameworkRegistry::getByName($componentName);
    if (!$component) {
        die(false);
    }

    $data = json_decode(urldecode($_POST['data']), true); // Note: it seems to be important to use $_POST instead of Tools::getValue
    $style = Tools::getValue('style');
    $component = $component::fetchWeb($data, $style, true);

    die(json_encode($component));
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
