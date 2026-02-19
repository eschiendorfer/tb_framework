<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class FancyboxDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'fancybox';
    protected const NAME = 'fancybox_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $categories = [
            ['id_category' => 12],
            ['id_category' => 227],
            ['id_category' => 235],
            ['id_category' => 422],
            ['id_category' => 426],
        ];

        $random_element = $categories[array_rand($categories)];
        $products = Product::getProducts(1, 0, 10, 'id_product', 'ASC', $random_element['id_category'], true);
        $images = [];

        foreach ($products as &$product) {
            $id_image = Product::getCover($product['id_product'])['id_image'];

            $images[] = [
                'entity_type' => ImageEntity::ENTITY_TYPE_PRODUCTS,
                'entity_id' => $id_image,
                'src' => '',
                'src_thumb' => '',
                'link_rewrite' => '',
            ];
        }

        return [
            'title' => 'Demo Images',
            'images' => $images,
            'thumbMax' => 0,
            'thumbSize' => 0,
        ];
    }
}



