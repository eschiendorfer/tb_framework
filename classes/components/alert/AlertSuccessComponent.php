<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class AlertSuccessComponent extends ComponentDefinition {
    protected const TYPE = 'alert';
    protected const NAME = 'alert_success';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_alert tbfw_alert_success',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}



