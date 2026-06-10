<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class BadgeSuccessCssToken extends CssTokenDefinition {
    protected const TYPE = 'badges';
    protected const NAME = 'badge_success';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_badge tbfw_badge_success',
        'small' => 'tbfw_badge tbfw_badge_success tbfw_badge_small',
        'large' => 'tbfw_badge tbfw_badge_success tbfw_badge_large',
    ];
}

