<?php

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../init.php');
include_once(dirname(__FILE__).'/autoload.php');

function tbFrameworkParseBool($value, bool $default = false): bool
{
    if (is_bool($value)) {
        return $value;
    }

    if (is_int($value)) {
        return $value !== 0;
    }

    if (is_string($value)) {
        $normalized = strtolower(trim($value));
        if (in_array($normalized, ['1', 'true', 'yes', 'y', 'on'], true)) {
            return true;
        }
        if (in_array($normalized, ['0', 'false', 'no', 'n', 'off', ''], true)) {
            return false;
        }
    }

    return $default;
}

function tbFrameworkBuildPanelConfig(array $payload): PanelConfig
{
    $renderMode = PanelRenderModeEnum::tryFrom((string)($payload['render_mode'] ?? '')) ?? PanelRenderModeEnum::DIALOG;
    $mobileMode = PanelMobileModeEnum::tryFrom((string)($payload['mobile_mode'] ?? '')) ?? PanelMobileModeEnum::DIALOG;
    $navLayer = PanelNavLayerEnum::tryFrom((string)($payload['nav_layer'] ?? '')) ?? PanelNavLayerEnum::OVER;
    $popoverPosition = PanelPopoverPositionEnum::tryFrom((string)($payload['popover_position'] ?? '')) ?? PanelPopoverPositionEnum::BOTTOM_CENTER;
    $popoverZIndex = PanelPopoverZIndexEnum::tryFrom((string)($payload['popover_z_index'] ?? '')) ?? PanelPopoverZIndexEnum::DEFAULT;

    $sheetSnapRatios = null;
    if (isset($payload['sheet_snap_ratios']) && is_array($payload['sheet_snap_ratios'])) {
        $sheetSnapRatios = array_map('floatval', $payload['sheet_snap_ratios']);
    }

    return new PanelConfig(
        renderMode: $renderMode,
        mobileMode: $mobileMode,
        navLayer: $navLayer,
        popoverPosition: $popoverPosition,
        popoverZIndex: $popoverZIndex,
        popoverMargin: max(0, (int)($payload['popover_margin'] ?? 10)),
        popoverAutoPosition: tbFrameworkParseBool($payload['popover_auto_position'] ?? true, true),
        anchorElementId: trim((string)($payload['anchor_element_id'] ?? '')),
        autoShow: tbFrameworkParseBool($payload['auto_show'] ?? false, false),
        toggleOnItemClick: tbFrameworkParseBool($payload['toggle_on_item_click'] ?? true, true),
        closeOnBackdrop: tbFrameworkParseBool($payload['close_on_backdrop'] ?? true, true),
        closeOnOutsideClick: tbFrameworkParseBool($payload['close_on_outside_click'] ?? true, true),
        showCloseButton: tbFrameworkParseBool($payload['show_close_button'] ?? true, true),
        closeOnSwipeDown: tbFrameworkParseBool($payload['close_on_swipe_down'] ?? true, true),
        pushHistoryState: tbFrameworkParseBool($payload['push_history_state'] ?? true, true),
        sheetSnapRatios: $sheetSnapRatios,
        sheetStartSnapIndex: max(0, (int)($payload['sheet_start_snap_index'] ?? 1))
    );
}

// Global Stuff
if (Tools::isSubmit('renderComponentWithAjax') && ($componentName = pSQL(Tools::getValue('component_name')))) {

    if (!Validate::isMailName($componentName)) {
        die(false); // Likely a hacking attempt
    }

    if ($componentName === 'toast') {
        trigger_error('tb_framework: AJAX component name "toast" is deprecated. Use "toast_default" instead.', E_USER_WARNING);
        $componentName = 'toast_default';
    }

    $component = FrameworkRegistry::getByName($componentName);
    if (!$component) {
        die(false);
    }

    $data = json_decode(urldecode($_POST['data']), true); // Note: it seems to be important to use $_POST instead of Tools::getValue
    if (!is_array($data)) {
        $data = [];
    }

    if ($componentName === 'panel_default') {
        $panelConfigPayload = (isset($data['config']) && is_array($data['config'])) ? $data['config'] : [];
        $data['config'] = tbFrameworkBuildPanelConfig($panelConfigPayload);
    }

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
