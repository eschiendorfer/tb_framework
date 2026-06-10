<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class ButtonSecondaryCssToken extends CssTokenDefinition {
    protected const TYPE = 'button';
    protected const NAME = 'button_secondary';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_button tbfw_button_secondary',
        'small' => 'tbfw_button tbfw_button_secondary tbfw_button_small',
        'large' => 'tbfw_button tbfw_button_secondary tbfw_button_large',
    ];
}

