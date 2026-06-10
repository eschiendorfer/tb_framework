<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class ButtonTertiaryCssToken extends CssTokenDefinition {
    protected const TYPE = 'button';
    protected const NAME = 'button_tertiary';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_button tbfw_button_tertiary',
        'small' => 'tbfw_button tbfw_button_tertiary tbfw_button_small',
        'large' => 'tbfw_button tbfw_button_tertiary tbfw_button_large',
    ];
}

