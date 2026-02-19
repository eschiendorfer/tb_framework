<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CardProductComponent extends ComponentDefinition {
    protected const TYPE = 'card';
    protected const NAME = 'card_product';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'list', 'scanner_purchase'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'default' => 'component/card/web/card_product.tpl',
            'list' => 'component/card/web/card_product/card_product_list.tpl',
            'scanner_purchase' => 'component/card/web/card_product/card_product_scanner_purchase.tpl',
        ],
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $products = Product::getProducts(1, 1, 1, 'id_product', 'ASC');
        $product = $products[0];

        $product['id_image'] = Product::getCover($product['id_product'])['id_image'];

        return Product::getProductProperties(1, $product);
    }
}



