<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class PriceReducedComponent extends ComponentDefinition {
    protected const TYPE = 'price';
    protected const NAME = 'price_reduced';
    protected const CHANNELS = [ComponentChannel::CSS_CLASSES];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['small', 'default', 'large'];
    protected const CSS_CLASSES_BY_STYLE = [
        'default' => 'tbfw_price_reduced',
        'small' => 'tbfw_price_reduced tbfw_price_size_small',
        'large' => 'tbfw_price_reduced tbfw_price_size_large',
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [];
    }
}




