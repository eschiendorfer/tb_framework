<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_0_1($module): bool
{
    $registerExistingHook = static function (string $hookName) use ($module): bool {
        if ((int)Hook::getIdByName($hookName) <= 0) {
            return true;
        }

        if (method_exists($module, 'isRegisteredInHook') && $module->isRegisteredInHook($hookName)) {
            return true;
        }

        return (bool)$module->registerHook($hookName);
    };

    if (!$registerExistingHook('actionRegisterAutoloader')) {
        return false;
    }

    $shortcodeRegistrationHook = 'actionGenzoShortcodesRegister';
    if ((int)Hook::getIdByName($shortcodeRegistrationHook) <= 0) {
        $hook = new Hook();
        $hook->name = $shortcodeRegistrationHook;
        $hook->title = 'Genzo Shortcodes Registration';
        $hook->description = 'Allows modules to register shortcode handler classes.';
        $hook->position = false;
        $hook->live_edit = false;

        if (!(bool)$hook->add()) {
            return false;
        }
    }

    return $registerExistingHook($shortcodeRegistrationHook);
}
