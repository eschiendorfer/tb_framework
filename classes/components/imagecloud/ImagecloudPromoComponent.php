<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ImagecloudPromoComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_promo';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $query = new DbQuery();
        $query->select('id_product');
        $query->from('product');
        $query->orderby('RAND()');
        $query->where('active=1');
        $query->limit(6);
        $ids_product = array_column(\Db::getInstance()->ExecuteS($query), 'id_product');

        $product_data = [];

        foreach ($ids_product as $id_product) {
            $product = new \Product($id_product, false, 1);
            $link = new \Link();

            $product_data[] = [
                'src' => $link->getImageLink($product->link_rewrite, $product->getCoverWs(), 'home_default'),
                'link' => [
                    'url' => $product->getLink(),
                    'title' => $product->name,
                ],
            ];
        }

        return [
            'header' => [
                'title' => 'Klassiker gehen immer oder nicht!?',
                'subtitle' => 'Unsere Lieblingsstücke',
            ],
            'promo' => [
                'src' => 'https://www.spielezar.ch/img/cms/cms/mitarbeiter/team-chesspoint.jpg',
                'position' => 'right',
            ],
            'data' => $product_data,
            'link' => [
                'title' => 'Alle anzeigen',
                'url' => '#',
            ],
        ];
    }
}
