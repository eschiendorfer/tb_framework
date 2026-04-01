<?php

require_once(dirname(__DIR__).'/enums/ComponentChannel.php');
require_once(dirname(__DIR__).'/EntityDataLoader.php');

final class CardDefaultDataBridge
{
    public const ENTITY_TYPE_PRODUCT = EntityDataLoader::ENTITY_TYPE_PRODUCT;
    public const ENTITY_TYPE_CATEGORY = EntityDataLoader::ENTITY_TYPE_CATEGORY;
    public const ENTITY_TYPE_MANUFACTURER = EntityDataLoader::ENTITY_TYPE_MANUFACTURER;

    public static function resolveEntityData(
        string $entityType,
        int $idEntity,
        ComponentChannel $channel
    ): array {
        $entityType = trim(strtolower($entityType));
        if ($idEntity <= 0) {
            return [];
        }

        $entityData = EntityDataLoader::loadByEntityType($entityType, $idEntity, $channel);
        if (empty($entityData)) {
            return [];
        }

        if ($entityType === self::ENTITY_TYPE_PRODUCT) {
            return self::mapToCardDefault(
                trim((string)($entityData['name'] ?? '')),
                self::resolveProductImageSrc($entityData),
                trim((string)($entityData['description_short'] ?? $entityData['description'] ?? $entityData['features_html'] ?? '')),
                self::resolveProductLinkUrl($entityData),
                self::resolveCategorySectionData($entityData)
            );
        }

        if ($entityType === self::ENTITY_TYPE_CATEGORY) {
            return self::mapToCardDefault(
                trim((string)($entityData['name'] ?? '')),
                trim((string)($entityData['image_src'] ?? '')),
                trim((string)($entityData['description'] ?? '')),
                trim((string)($entityData['link'] ?? ''))
            );
        }

        if ($entityType === self::ENTITY_TYPE_MANUFACTURER) {
            return self::mapToCardDefault(
                trim((string)($entityData['name'] ?? '')),
                trim((string)($entityData['image_src'] ?? '')),
                trim((string)($entityData['description'] ?? '')),
                trim((string)($entityData['link'] ?? ''))
            );
        }

        return [];
    }

    private static function mapToCardDefault(
        string $title,
        string $imageSrc,
        string $description = '',
        string $linkUrl = '',
        array $section = []
    ): array {
        if ($title === '' || $imageSrc === '') {
            return [];
        }

        $data = [
            'title' => $title,
            'image' => [
                'src' => $imageSrc,
            ],
        ];

        if ($description !== '') {
            $data['description'] = $description;
        }

        if ($linkUrl !== '') {
            $data['link'] = [
                'url' => $linkUrl,
            ];
        }

        if (!empty($section)) {
            $data['section'] = $section;
        }

        return $data;
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

    private static function resolveCategorySectionData(array $product): array
    {
        $idCategory = (int)($product['id_category_default'] ?? 0);
        if ($idCategory <= 0) {
            return [];
        }

        $categoryData = EntityDataLoader::loadByEntityType(self::ENTITY_TYPE_CATEGORY, $idCategory);
        $title = trim((string)($categoryData['name'] ?? ''));
        $url = trim((string)($categoryData['link'] ?? ''));

        if ($title === '' || $url === '') {
            return [];
        }

        return [
            'title' => $title,
            'url' => $url,
        ];
    }
}
