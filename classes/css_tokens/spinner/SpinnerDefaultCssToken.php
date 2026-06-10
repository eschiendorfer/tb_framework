<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class SpinnerDefaultCssToken extends CssTokenDefinition {
    protected const TYPE = 'spinner';
    protected const NAME = 'spinner_default';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_spinner_default',
    ];
}

