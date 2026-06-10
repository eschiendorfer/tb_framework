<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ImagecloudPromoComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_promo';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
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

        $idLang = (int)(\Context::getContext()->language->id ?? \Configuration::get('PS_LANG_DEFAULT'));

        foreach ($ids_product as $id_product) {
            $product = new \Product((int)$id_product, false, $idLang);
            $imageUrl = self::getProductImageUrl($product);
            if ($imageUrl === '') {
                continue;
            }

            $product_data[] = [
                'src' => $imageUrl,
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
                'src' => _THEME_DIR_ . 'img/home/gutscheine-ad.webp',
                'position' => 'right',
            ],
            'data' => $product_data,
            'link' => [
                'title' => 'Alle anzeigen',
                'url' => '#',
            ],
        ];
    }

    public static function getProductImageUrl(\Product $product, string $imageType = 'medium_default'): string
    {
        $idProduct = (int)$product->id;
        if ($idProduct <= 0) {
            return '';
        }

        $idLang = (int)(\Context::getContext()->language->id ?? \Configuration::get('PS_LANG_DEFAULT'));
        $linkRewrite = self::getLocalizedValue($product->link_rewrite, $idLang);
        if ($linkRewrite === '') {
            return '';
        }

        $cover = \Product::getCover($idProduct);
        $idImage = (int)($cover['id_image'] ?? 0);
        if ($idImage <= 0) {
            return '';
        }

        $link = \Context::getContext()->link;
        if (!$link instanceof \Link) {
            $link = new \Link(null, \Tools::getShopProtocol());
        }

        return self::normalizeImageUrl((string)$link->getImageLink($linkRewrite, $idImage, $imageType));
    }

    private static function getLocalizedValue($value, int $idLang): string
    {
        if (is_array($value)) {
            $value = $value[$idLang] ?? reset($value);
        }

        return trim((string)$value);
    }

    private static function normalizeImageUrl(string $url): string
    {
        $parts = parse_url($url);
        if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host']) || empty($parts['path'])) {
            return $url;
        }

        $host = (string)$parts['host'];
        $path = (string)$parts['path'];
        $normalizedPath = preg_replace(
            '#^/(?:' . preg_quote($host, '#') . '|(?:[a-z0-9-]+\.)+[a-z]{2,})(/|$)#i',
            '/',
            $path
        );
        if (!is_string($normalizedPath) || $normalizedPath === $path) {
            return $url;
        }

        $authority = $parts['scheme'] . '://' . $host . (isset($parts['port']) ? ':' . $parts['port'] : '');
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        return $authority . $normalizedPath . $query . $fragment;
    }
}
