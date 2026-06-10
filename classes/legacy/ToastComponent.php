<?php

require_once(dirname(__DIR__) . '/components/toast/ToastDefaultComponent.php');

final class ToastComponent
{
    private static function logDeprecated(): void
    {
        trigger_error('tb_framework: ToastComponent is deprecated. Use ToastDefaultComponent / toast_default instead.', E_USER_WARNING);
    }

    public static function fetchWeb(array $data = [], string $style = '', bool $ajax = false)
    {
        self::logDeprecated();
        return ToastDefaultComponent::fetchWeb($data, $style, $ajax);
    }

    public static function fetchEmail(array $data = [], string $style = '', bool $ajax = false)
    {
        self::logDeprecated();
        return ToastDefaultComponent::fetchEmail($data, $style, $ajax);
    }
}