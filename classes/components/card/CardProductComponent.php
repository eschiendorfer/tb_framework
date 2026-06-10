<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CardProductComponent extends ComponentDefinition {
    protected const TYPE = 'card';
    protected const NAME = 'card_product';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB, \CoreExtension\OutputChannelEnum::EMAIL];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'list', 'scanner_purchase'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'default' => 'component/card/web/card_product.tpl',
            'list' => 'component/card/web/card_product/card_product_list.tpl',
            'scanner_purchase' => 'component/card/web/card_product/card_product_scanner_purchase.tpl',
        ],
        'email' => [
            'default' => 'component/card/email/card_product.tpl',
        ],
    ];

    public function validate(array &$data): void {
        $productUrl = $this->resolveProductUrl($data);
        if ($productUrl !== '') {
            $data['product_url'] = $this->toAbsoluteUrl($productUrl);
        }

        $imageUrl = $this->resolveProductImageUrl($data);
        if ($imageUrl !== '') {
            $data['image_url'] = $this->toAbsoluteUrl($imageUrl);
        }

        if (empty($data['link']) && !empty($data['product_url'])) {
            $data['link'] = $data['product_url'];
        }

        if (empty($data['img']) && !empty($data['image_url'])) {
            $data['img'] = $data['image_url'];
        }
    }

    public function getDemoData(): array {
        $products = Product::getProducts(1, 1, 1, 'id_product', 'ASC');
        $product = $products[0];

        $product['id_image'] = Product::getCover($product['id_product'])['id_image'];

        return Product::getProductProperties(1, $product);
    }

    private function resolveProductUrl(array $data): string {
        $productUrl = trim((string)($data['product_url'] ?? ''));
        if ($productUrl !== '') {
            return $productUrl;
        }

        $link = trim((string)($data['link'] ?? ''));
        if ($link !== '') {
            return $link;
        }

        $idProduct = (int)($data['id_product'] ?? 0);
        if ($idProduct <= 0) {
            return '';
        }

        $linkRewrite = (string)($data['link_rewrite'] ?? '');

        return (string)Context::getContext()->link->getProductLink($idProduct, $linkRewrite);
    }

    private function resolveProductImageUrl(array $data): string {
        $imageUrl = trim((string)($data['image_url'] ?? ''));
        if ($imageUrl !== '') {
            return $imageUrl;
        }

        $image = trim((string)($data['img'] ?? ''));
        if ($image !== '') {
            return $image;
        }

        $idProduct = (int)($data['id_product'] ?? 0);
        $idImage = (int)($data['id_image'] ?? 0);

        if ($idImage <= 0 && $idProduct > 0) {
            $cover = Product::getCover($idProduct);
            $idImage = (int)($cover['id_image'] ?? 0);
        }

        if ($idImage <= 0) {
            return '';
        }

        $linkRewrite = (string)($data['link_rewrite'] ?? '');

        return (string)Context::getContext()->link->getImageLink($linkRewrite, $idImage, 'home_default');
    }

    private function toAbsoluteUrl(string $url): string {
        if (class_exists('ImageHelper')) {
            return ImageHelper::convertToAbsoluteUrl($url);
        }

        return $url;
    }
}



