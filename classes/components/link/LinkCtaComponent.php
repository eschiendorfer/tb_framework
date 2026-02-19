<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class LinkCtaComponent extends ComponentDefinition {
    protected const TYPE = 'link';
    protected const NAME = 'link_cta';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_link_cta tbfw_link_cta',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}

