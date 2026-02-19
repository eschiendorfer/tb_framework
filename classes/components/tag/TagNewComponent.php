<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class TagNewComponent extends ComponentDefinition {
    protected const TYPE = 'tag';
    protected const NAME = 'tag_new';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_tag_new',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}



