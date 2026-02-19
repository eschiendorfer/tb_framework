<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class BadgeDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'badges';
    protected const NAME = 'badge_default';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const DEFAULT_STYLE = 'default';
    protected const STYLES = ['small', 'default', 'large'];
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_badge tbfw_badge_default',
        'small' => 'tbfw_badge tbfw_badge_default tbfw_badge_small',
        'large' => 'tbfw_badge tbfw_badge_default tbfw_badge_large',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}




