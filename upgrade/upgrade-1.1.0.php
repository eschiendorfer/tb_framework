<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_1_0($module): bool
{
    if (!tbFrameworkUpgrade110RegisterExistingHook($module, 'moduleRoutes')) {
        return false;
    }

    if (!tbFrameworkUpgrade110RegisterExistingHook($module, 'actionRegisterAutoloader')) {
        return false;
    }

    if (!tbFrameworkUpgrade110EnsureHookExists('actionGenzoShortcodesRegister')) {
        return false;
    }

    if (!tbFrameworkUpgrade110RegisterExistingHook($module, 'actionGenzoShortcodesRegister')) {
        return false;
    }

    if (!tbFrameworkUpgrade110RegisterExistingHook($module, 'displayHeader')) {
        return false;
    }

    if (!tbFrameworkUpgrade110RegisterExistingHook($module, 'displayTab')) {
        return false;
    }

    return tbFrameworkUpgrade110RegisterExistingHook($module, 'displayBottomColumn');
}

function tbFrameworkUpgrade110RegisterExistingHook(Module $module, string $hookName): bool
{
    if ((int)Hook::getIdByName($hookName) <= 0) {
        return true;
    }

    if (method_exists($module, 'isRegisteredInHook') && $module->isRegisteredInHook($hookName)) {
        return true;
    }

    return (bool)$module->registerHook($hookName);
}

function tbFrameworkUpgrade110EnsureHookExists(string $hookName): bool
{
    if ((int)Hook::getIdByName($hookName) > 0) {
        return true;
    }

    $hook = new Hook();
    $hook->name = $hookName;
    $hook->title = 'Genzo Shortcodes Registration';
    $hook->description = 'Allows modules to register shortcode handler classes.';
    $hook->position = false;
    $hook->live_edit = false;

    return (bool)$hook->add();
}