<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ListCompactComponent extends ComponentDefinition {
    protected const TYPE = 'list';
    protected const NAME = 'list_compact';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $context = Context::getContext();

        $query = new \DbQuery();
        $query->select('p.*, pl.*, cl.name AS category, m.name AS manufacturer');
        $query->from('product', 'p');
        $query->innerJoin('product_shop', 'ps', 'ps.id_product=p.id_product AND ps.id_shop='.$context->shop->id);
        $query->innerJoin('product_lang', 'pl', 'pl.id_product=p.id_product');
        $query->innerJoin('category_lang', 'cl', 'cl.id_category=p.id_category_default AND cl.id_lang='.$context->language->id);
        $query->leftJoin('manufacturer', 'm', 'm.id_manufacturer=p.id_manufacturer AND m.active=1');
        $query->orderBy('p.active DESC, RAND()');
        $query->limit('5');
        $products =\Db::getInstance()->ExecuteS($query);

        $data = [];

        foreach ($products as $product) {
            $manufacturer = $product['manufacturer'] ? ' from '.$product['manufacturer'] : '';

            $price_selling = Tools::displayPrice(Product::getPriceStatic($product['id_product']));
            $price_drop = Tools::displayPrice(5.95);

            $css_price_default = PriceDefaultComponent::fetchCssClasses();
            $css_price_reduced = PriceDefaultComponent::fetchCssClasses();
            $css_price_original = PriceDefaultComponent::fetchCssClasses();

            if ($price_drop) {
                $price_content = "<span class='{$css_price_reduced}'>{$price_selling}</span> <span class='{$css_price_original}'>{$price_drop}</span>";
            }
            else {
                $price_content = "<span class='{$css_price_default}'>{$price_selling}</span>";
            }

            $price_content = '';

            // Info: that is the correct structure of one row
            $data[] = [
                'img' => $context->link->getImageLink($product['link_rewrite'], Product::getCover($product['id_product'])['id_image'], 'small_default'),
                'title' => $product['name'],
                'subtitle' => $product['category'].$manufacturer,
                'link' => ['url' => $context->link->getProductLink($product['id_product'])],
                'element_columns' => []
            ];
        }

        $titles = ['Brettspiele', 'Puzzles', 'Gesselschaftsspiele', 'TCG', 'Merchandise', 'Klassiker', 'Kinderspiele', 'Leuchtpuzzles'];

        return [
            'title' => $titles[array_rand($titles)],
            'data' => $data,
            'button' => [
                'title' => 'View All',
                'link'  => ['url' => '#'],
                'style' => 'width: 100%;',
            ],
        ];
    }
}




