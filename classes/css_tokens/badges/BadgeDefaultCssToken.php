<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class BadgeDefaultCssToken extends CssTokenDefinition {
    protected const TYPE = 'badges';
    protected const NAME = 'badge_default';

    protected const DEFAULT_STYLE = 'default';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_badge tbfw_badge_default',
        'small' => 'tbfw_badge tbfw_badge_default tbfw_badge_small',
        'large' => 'tbfw_badge tbfw_badge_default tbfw_badge_large',
    ];
}

