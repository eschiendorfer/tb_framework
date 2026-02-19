<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class StockAvailableComponent extends ComponentDefinition {
    protected const TYPE = 'stock';
    protected const NAME = 'stock_available';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_stock_available',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}



