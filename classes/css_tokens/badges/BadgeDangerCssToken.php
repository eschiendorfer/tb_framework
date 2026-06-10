<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class BadgeDangerCssToken extends CssTokenDefinition {
    protected const TYPE = 'badges';
    protected const NAME = 'badge_danger';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_badge tbfw_badge_danger',
        'small' => 'tbfw_badge tbfw_badge_danger tbfw_badge_small',
        'large' => 'tbfw_badge tbfw_badge_danger tbfw_badge_large',
    ];
}

