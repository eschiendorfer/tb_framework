<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class PriceReducedCssToken extends CssTokenDefinition {
    protected const TYPE = 'price';
    protected const NAME = 'price_reduced';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_price_reduced',
        'small' => 'tbfw_price_reduced tbfw_price_size_small',
        'large' => 'tbfw_price_reduced tbfw_price_size_large',
    ];
}

