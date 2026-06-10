<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class ButtonCtaCssToken extends CssTokenDefinition {
    protected const TYPE = 'button';
    protected const NAME = 'button_cta';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_button tbfw_button_cta',
        'small' => 'tbfw_button tbfw_button_cta tbfw_button_small',
        'large' => 'tbfw_button tbfw_button_cta tbfw_button_large',
    ];
}

