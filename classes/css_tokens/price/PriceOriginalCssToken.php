<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class PriceOriginalCssToken extends CssTokenDefinition {
    protected const TYPE = 'price';
    protected const NAME = 'price_original';

    protected const STYLES = ['small', 'default', 'large'];

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_price_original',
        'small' => 'tbfw_price_original tbfw_price_size_small',
        'large' => 'tbfw_price_original tbfw_price_size_large',
    ];
}

