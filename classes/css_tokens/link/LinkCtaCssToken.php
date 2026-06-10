<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class LinkCtaCssToken extends CssTokenDefinition {
    protected const TYPE = 'link';
    protected const NAME = 'link_cta';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_link_cta tbfw_link_cta',
    ];
}

