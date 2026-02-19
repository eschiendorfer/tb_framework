<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ButtonPrimaryComponent extends ComponentDefinition {
    protected const TYPE = 'button';
    protected const NAME = 'button_primary';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES, ComponentChannel::EMAIL];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'small', 'large'];
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_button tbfw_button_primary',
        'small' => 'tbfw_button tbfw_button_primary tbfw_button_small',
        'large' => 'tbfw_button tbfw_button_primary tbfw_button_large',
    ];

    public function validate(array &$data): void {

    }

    public function getDemoData(): array {
        return [];
    }
}



