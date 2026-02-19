<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class BadgeDangerComponent extends ComponentDefinition {
    protected const TYPE = 'badges';
    protected const NAME = 'badge_danger';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['small', 'default', 'large'];
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_badge tbfw_badge_danger',
        'small' => 'tbfw_badge tbfw_badge_danger tbfw_badge_small',
        'large' => 'tbfw_badge tbfw_badge_danger tbfw_badge_large',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}




