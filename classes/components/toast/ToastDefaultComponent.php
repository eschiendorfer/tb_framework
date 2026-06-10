<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ToastDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'toast';
    protected const NAME = 'toast_default';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;


    protected const STYLES = ['default', 'success', 'danger', 'warning'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'default' => 'component/toast/web/toast_default.tpl',
            'success' => 'component/toast/web/toast_default/toast_default_success.tpl',
            'danger' => 'component/toast/web/toast_default/toast_default_danger.tpl',
            'warning' => 'component/toast/web/toast_default/toast_default_warning.tpl',
        ],
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'html' => '<div>Ich bin ein Toast</div>',
            'hide_after' => 5000,
        ];
    }
}



