<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class BadgeWarningCssToken extends CssTokenDefinition {
    protected const TYPE = 'badges';
    protected const NAME = 'badge_warning';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_badge tbfw_badge_warning',
        'small' => 'tbfw_badge tbfw_badge_warning tbfw_badge_small',
        'large' => 'tbfw_badge tbfw_badge_warning tbfw_badge_large',
    ];
}

