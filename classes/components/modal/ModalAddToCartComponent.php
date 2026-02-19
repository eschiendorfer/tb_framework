<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ModalAddToCartComponent extends ComponentDefinition {
    protected const TYPE = 'modal';
    protected const NAME = 'modal_add_to_cart';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        $this->applyDefaultTriggers($data);
    }

    public function getDemoData(): array {
        $products = Product::getProducts(1, 1, 1, 'id_product', 'ASC');
        $product = $products[0];
        $product['id_image'] = Product::getCover($product['id_product'])['id_image'];
        $product['total_wt'] = 10.15;
        $product = Product::getProductProperties(1, $product);

        $cart_summary = [
            'total_products_wt' => 10.25,
            'total_shipping'    => 4.00,
            'total_price'       => 14.25,
        ];

        return [
            'product' => $product,
            'cart_summary' => $cart_summary,
            'title' => 'Custom Modal',
            'width' => 'medium',
            'height' => 'auto',
        ];
    }

    private function applyDefaultTriggers(array &$data): void {
        if (!isset($data['triggers_show']) || !is_array($data['triggers_show'])) {
            $data['triggers_show'] = ['auto_show', 'click_item'];
        }

        if (!isset($data['triggers_close']) || !is_array($data['triggers_close'])) {
            $data['triggers_close'] = ['click_close_button', 'click_item', 'click_outside'];
        }
    }
}



