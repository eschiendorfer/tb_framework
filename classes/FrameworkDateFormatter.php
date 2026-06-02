<?php

final class FrameworkDateFormatter
{
    private static $module = null;

    public static function formatDateToTimeElapsed($datetime): string
    {
        $module = self::getModule();
        if (!$module instanceof Module) {
            return '';
        }

        $now = new DateTime();
        $ago = new DateTime((string)$datetime);
        $diff = $now->diff($ago);

        if ($diff->y) {
            return sprintf($module->l('%d years ago', 'FrameworkDateFormatter'), $diff->y);
        }

        if ($diff->m) {
            return sprintf($module->l('%d months ago', 'FrameworkDateFormatter'), $diff->m);
        }

        if ($diff->d) {
            return sprintf($module->l('%d days ago', 'FrameworkDateFormatter'), $diff->d);
        }

        if ($diff->h) {
            return sprintf($module->l('%d hours ago', 'FrameworkDateFormatter'), $diff->h);
        }

        if ($diff->i) {
            return sprintf($module->l('%d minutes ago', 'FrameworkDateFormatter'), $diff->i);
        }

        if ($diff->s) {
            return sprintf($module->l('%d seconds ago', 'FrameworkDateFormatter'), $diff->s);
        }

        return $module->l('just now', 'FrameworkDateFormatter');
    }

    private static function getModule()
    {
        if (self::$module === null) {
            self::$module = Module::getInstanceByName('tb_framework');
        }

        return self::$module;
    }
}
