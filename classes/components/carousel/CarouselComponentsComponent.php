<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CarouselComponentsComponent extends ComponentDefinition {
    protected const TYPE = 'carousel';
    protected const NAME = 'carousel_components';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (!isset($data['nbr_columns'])) {
            $data['nbr_columns'] = count($data['slides']);
        }
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
        $productBoxes = [];

        foreach ($products as &$product) {
            $product['id_image'] = Product::getCover($product['id_product'])['id_image'];
            $product = Product::getProductProperties(1, $product);
            $productBoxes[] = CardProductComponent::fetchWeb($product);
        }

        return [
            'column_width' => '250px',
            'nbr_columns' => 3.5,
            'slides' => $productBoxes
        ];
    }
}



