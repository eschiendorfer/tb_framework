<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class HeaderDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'header';
    protected const NAME = 'header_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'title' => 'Die Meisten sind richtig gut',
            'subtitle' => 'Marken & Verlage',
            'description' => 'The market has like one million brands. 99% of them don\'t fit our expectation. Only the best one will be listed on our site...',
        ];
    }
}



