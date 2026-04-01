<?php

require_once(dirname(__DIR__).'/enums/ComponentChannel.php');
require_once(dirname(__DIR__).'/EntityDataLoader.php');

final class CardTeaserDataBridge
{
    public const ENTITY_TYPE_PRODUCT = EntityDataLoader::ENTITY_TYPE_PRODUCT;
    public const ENTITY_TYPE_MANUFACTURER = EntityDataLoader::ENTITY_TYPE_MANUFACTURER;
    public const ENTITY_TYPE_BLOG = EntityDataLoader::ENTITY_TYPE_BLOG;

    public static function resolveEntityData(
        string $entityType,
        int $idEntity,
        array $params,
        ComponentChannel $channel
    ): array {
        $entityType = trim(strtolower($entityType));
        if ($idEntity <= 0) {
            return [];
        }

        if ($entityType === self::ENTITY_TYPE_PRODUCT) {
            return self::mapProductData($idEntity, $params, $channel);
        }

        if ($entityType === self::ENTITY_TYPE_MANUFACTURER) {
            return self::mapManufacturerData($idEntity, $params, $channel);
        }

        if ($entityType === self::ENTITY_TYPE_BLOG) {
            return self::mapBlogData($idEntity, $params, $channel);
        }

        return [];
    }

    private static function mapProductData(int $idProduct, array $params, ComponentChannel $channel): array
    {
        $product = EntityDataLoader::loadByEntityType(self::ENTITY_TYPE_PRODUCT, $idProduct, $channel);
        if (empty($product)) {
            return [];
        }

        $title = trim((string)($product['name'] ?? ''));
        $url = self::resolveProductLinkUrl($product);
        $imageSrc = self::resolveProductImageSrc($product);

        if ($title === '' || $url === '' || $imageSrc === '') {
            return [];
        }

        $price = '';
        if (isset($product['price'])) {
            $price = \Tools::displayPrice((float)$product['price']);
        } elseif ($idProduct > 0) {
            $price = \Tools::displayPrice((float)\Product::getPriceStatic($idProduct));
        }

        return [
            'title' => $title,
            'link' => [
                'url' => $url,
            ],
            'image' => [
                'src' => $imageSrc,
            ],
            'description' => trim((string)($params['description'] ?? '')),
            'short_description' => trim((string)($product['description_short'] ?? '')),
            'price' => $price,
        ];
    }

    private static function mapManufacturerData(int $idManufacturer, array $params, ComponentChannel $channel): array
    {
        $manufacturer = EntityDataLoader::loadByEntityType(self::ENTITY_TYPE_MANUFACTURER, $idManufacturer, $channel);
        if (empty($manufacturer)) {
            return [];
        }

        $title = trim((string)($manufacturer['name'] ?? ''));
        $url = trim((string)($manufacturer['link'] ?? ''));
        $imageSrc = trim((string)($manufacturer['image_src'] ?? ''));

        if ($title === '' || $url === '' || $imageSrc === '') {
            return [];
        }

        return [
            'title' => $title,
            'link' => [
                'url' => $url,
            ],
            'image' => [
                'src' => $imageSrc,
            ],
            'description' => trim((string)($params['description'] ?? '')),
            'short_description' => trim((string)($manufacturer['description'] ?? '')),
            'price' => '',
        ];
    }

    private static function mapBlogData(int $idSimpleBlogPost, array $params, ComponentChannel $channel): array
    {
        $blogpost = EntityDataLoader::loadByEntityType(self::ENTITY_TYPE_BLOG, $idSimpleBlogPost, $channel);
        if (empty($blogpost)) {
            return [];
        }

        $title = trim((string)($blogpost['title'] ?? ''));
        $url = trim((string)($blogpost['url'] ?? ''));
        $imageSrc = trim((string)($blogpost['image_src'] ?? ''));

        if ($title === '' || $url === '' || $imageSrc === '') {
            return [];
        }

        return [
            'title' => $title,
            'link' => [
                'url' => $url,
            ],
            'image' => [
                'src' => $imageSrc,
            ],
            'description' => trim((string)($params['description'] ?? '')),
            'short_description' => trim((string)($blogpost['short_description'] ?? '')),
            'price' => '',
        ];
    }

    private static function resolveProductLinkUrl(array $product): string
    {
        $link = trim((string)($product['link'] ?? ''));
        if ($link !== '') {
            return $link;
        }

        $idProduct = (int)($product['id_product'] ?? 0);
        if ($idProduct <= 0) {
            return '';
        }

        $linkRewrite = (string)($product['link_rewrite'] ?? '');
        return (string)\Context::getContext()->link->getProductLink($idProduct, $linkRewrite);
    }

    private static function resolveProductImageSrc(array $product): string
    {
        $imageSrc = trim((string)($product['img'] ?? ''));
        if ($imageSrc !== '') {
            return $imageSrc;
        }

        $idProduct = (int)($product['id_product'] ?? 0);
        $idImage = (int)($product['id_image'] ?? 0);

        if ($idImage <= 0 && $idProduct > 0) {
            $cover = \Product::getCover($idProduct);
            $idImage = (int)($cover['id_image'] ?? 0);
        }

        if ($idImage <= 0) {
            return '';
        }

        $linkRewrite = (string)($product['link_rewrite'] ?? '');
        return (string)\Context::getContext()->link->getImageLink($linkRewrite, $idImage, 'home_default');
    }
}
