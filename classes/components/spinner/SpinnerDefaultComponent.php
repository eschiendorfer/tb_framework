<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class SpinnerDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'spinner';
    protected const NAME = 'spinner_default';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_spinner_default',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}



