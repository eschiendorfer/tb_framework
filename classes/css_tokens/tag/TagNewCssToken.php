<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class TagNewCssToken extends CssTokenDefinition {
    protected const TYPE = 'tag';
    protected const NAME = 'tag_new';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_tag_new',
    ];
}

