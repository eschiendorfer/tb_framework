<?php

require_once(dirname(__DIR__, 2).'/CssTokenDefinition.php');

class StockPreorderCssToken extends CssTokenDefinition {
    protected const TYPE = 'stock';
    protected const NAME = 'stock_preorder';

    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_stock_preorder',
    ];
}

