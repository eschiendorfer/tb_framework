<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ProgressbarDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'progressbar';
    protected const NAME = 'progressbar_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'progress_percentage' => rand(5,100),
        ];
    }
}



