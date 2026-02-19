<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ButtonSecondaryComponent extends ComponentDefinition {
    protected const TYPE = 'button';
    protected const NAME = 'button_secondary';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['small', 'default', 'large'];
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_button tbfw_button_secondary',
        'small' => 'tbfw_button tbfw_button_secondary tbfw_button_small',
        'large' => 'tbfw_button tbfw_button_secondary tbfw_button_large',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}




