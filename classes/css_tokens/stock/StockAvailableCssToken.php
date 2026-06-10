<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class StockAvailableCssToken extends CssTokenDefinition {
    protected const TYPE = 'stock';
    protected const NAME = 'stock_available';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_stock_available',
    ];
}

