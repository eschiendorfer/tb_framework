<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__).'/card/CardProductComponent.php');

class GridProductComponent extends ComponentDefinition {
    protected const TYPE = 'grid';
    protected const NAME = 'grid_product';
    protected const CHANNELS = [ComponentChannel::EMAIL];
    protected const SUPPORTS_CACHING = false;
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'email' => [
            'default' => 'component/grid/email/grid_product.tpl',
        ],
    ];

    public function validate(array &$data): void {
        $products = $data['products'] ?? [];
        $nbrColumns = (int)($data['nbr_columns'] ?? 2);
        if ($nbrColumns <= 0) {
            $nbrColumns = 2;
        }

        if (!is_array($products)) {
            $products = [];
        }

        $cards = [];
        foreach ($products as $product) {
            if (!is_array($product)) {
                continue;
            }

            $cards[] = CardProductComponent::fetchEmail($product);
        }

        $title = '';
        if (isset($data['title'])) {
            $title = trim((string)$data['title']);
        }

        $button = [];
        if (!empty($data['button']) && is_array($data['button'])) {
            $buttonTitle = trim((string)($data['button']['title'] ?? ''));
            $buttonUrl = trim((string)($data['button']['link']['url'] ?? ''));
            if ($buttonTitle !== '') {
                $button = [
                    'title' => $buttonTitle,
                    'link' => ['url' => $buttonUrl],
                ];
            }
        }

        $rows = [];
        foreach (array_chunk($cards, $nbrColumns) as $cardsRow) {
            $row = [];
            for ($i = 0; $i < $nbrColumns; $i++) {
                $row[] = $cardsRow[$i] ?? '';
            }
            $rows[] = $row;
        }

        $data['title'] = $title;
        $data['button'] = $button;
        $data['nbr_columns'] = $nbrColumns;
        $data['column_width'] = 100 / $nbrColumns;
        $data['rows'] = $rows;
    }

    public function getDemoData(): array {
        $context = Context::getContext();

        $query = new \DbQuery();
        $query->select('p.*, pl.*');
        $query->from('product', 'p');
        $query->innerJoin('product_shop', 'ps', 'ps.id_product=p.id_product AND ps.id_shop='.(int)$context->shop->id);
        $query->innerJoin('product_lang', 'pl', 'pl.id_product=p.id_product AND pl.id_lang='.(int)$context->language->id);
        $query->orderBy('p.active DESC, RAND()');
        $query->limit('6');
        $products = \Db::getInstance()->ExecuteS($query);

        $data = [];
        foreach ($products as $product) {
            $idProduct = (int)$product['id_product'];
            $cover = Product::getCover($idProduct);
            $idImage = $cover['id_image'] ?? 0;

            $price = (float)Product::getPriceStatic($idProduct, true, null, _TB_PRICE_DATABASE_PRECISION_, null, false, true);
            $priceWithoutReduction = (float)Product::getPriceStatic($idProduct, true, null, _TB_PRICE_DATABASE_PRECISION_, null, false, false);

            $data[] = [
                'name' => (string)$product['name'],
                'price' => $price,
                'reduction' => max(0.0, $priceWithoutReduction - $price),
                'price_without_reduction' => $priceWithoutReduction,
                'product_url' => $context->link->getProductLink($idProduct),
                'image_url' => $context->link->getImageLink((string)$product['link_rewrite'], (string)$idImage, 'home_default'),
            ];
        }

        return [
            'title' => 'Persönliche Produktempfehlungen',
            'button' => [
                'title' => 'Alle Produkte ansehen',
                'link' => ['url' => '#'],
            ],
            'nbr_columns' => 2,
            'products' => $data,
        ];
    }
}
