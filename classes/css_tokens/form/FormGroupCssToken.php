<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class FormGroupCssToken extends CssTokenDefinition {
    protected const TYPE = 'form';
    protected const NAME = 'form_group';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_form_group',
    ];
}

