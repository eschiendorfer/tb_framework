<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ToastComponent extends ComponentDefinition {
    protected const TYPE = 'toast';
    protected const NAME = 'toast';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;


    protected const STYLES = ['default', 'success', 'danger', 'warning'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'success' => 'component/toast/web/toast/toast_success.tpl',
            'danger' => 'component/toast/web/toast/toast_danger.tpl',
            'warning' => 'component/toast/web/toast/toast_warning.tpl',
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



