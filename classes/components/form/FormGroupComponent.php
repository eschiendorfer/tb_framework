<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class FormGroupComponent extends ComponentDefinition {
    protected const TYPE = 'form';
    protected const NAME = 'form_group';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_form_group',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}

