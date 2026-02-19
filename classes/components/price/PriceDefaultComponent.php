<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class PriceDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'price';
    protected const NAME = 'price_default';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'small', 'large'];
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_price_default',
        'small' => 'tbfw_price_default tbfw_price_size_small',
        'large' => 'tbfw_price_default tbfw_price_size_large',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}



