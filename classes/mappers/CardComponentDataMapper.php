<?php

final class CardComponentDataMapper
{
    public const ENTITY_TYPE_PRODUCT = 'product';
    public const ENTITY_TYPE_CATEGORY = 'category';
    public const ENTITY_TYPE_MANUFACTURER = 'manufacturer';
    public const ENTITY_TYPE_BLOG = 'blog';
    public const ENTITY_TYPE_MANUAL = 'manual';

    public static function resolveCardProduct(
        string $entityType,
        int $idEntity,
        \CoreExtension\OutputChannelEnum $channel
    ): array {
        if (self::normalizeEntityType($entityType) !== self::ENTITY_TYPE_PRODUCT || $idEntity <= 0) {
            return [];
        }

        return self::loadEntityData(\CoreExtension\EntityTypeEnum::PRODUCT, $idEntity, $channel);
    }

    public static function resolveCardDefault(
        string $entityType,
        int $idEntity,
        \CoreExtension\OutputChannelEnum $channel
    ): array {
        $entityType = self::normalizeEntityType($entityType);
        if ($idEntity <= 0) {
            return [];
        }

        if ($entityType === self::ENTITY_TYPE_PRODUCT) {
            $product = self::loadEntityData(\CoreExtension\EntityTypeEnum::PRODUCT, $idEntity, $channel);
            if (empty($product)) {
                return [];
            }

            return self::mapToCardDefault(
                trim((string)($product['name'] ?? '')),
                self::getProductImageUrl($product),
                trim((string)($product['description_short'] ?? $product['description'] ?? '')),
                self::getProductUrl($product),
                self::resolveProductCategorySection($product)
            );
        }

        if ($entityType === self::ENTITY_TYPE_CATEGORY || $entityType === self::ENTITY_TYPE_MANUFACTURER) {
            $entityData = self::loadEntityDataByKey($entityType, $idEntity, $channel);

            return self::mapToCardDefault(
                trim((string)($entityData['title'] ?? '')),
                self::getImageUrl($entityData),
                trim((string)($entityData['subtitle'] ?? '')),
                self::getUrl($entityData)
            );
        }

        return [];
    }

    public static function resolveCardTeaser(
        string $entityType,
        int $idEntity,
        array $params,
        \CoreExtension\OutputChannelEnum $channel
    ): array {
        $entityType = self::normalizeEntityType($entityType);
        if ($idEntity <= 0) {
            return [];
        }

        if ($entityType === self::ENTITY_TYPE_PRODUCT) {
            $product = self::loadEntityData(\CoreExtension\EntityTypeEnum::PRODUCT, $idEntity, $channel);
            if (empty($product)) {
                return [];
            }

            return self::mapToCardTeaser(
                trim((string)($product['name'] ?? '')),
                self::getProductUrl($product),
                self::getProductImageUrl($product),
                trim((string)($params['description'] ?? '')),
                trim((string)($product['description_short'] ?? '')),
                trim((string)($product['price_text'] ?? ''))
            );
        }

        if ($entityType === self::ENTITY_TYPE_MANUFACTURER || $entityType === self::ENTITY_TYPE_BLOG) {
            $entityData = self::loadEntityDataByKey($entityType, $idEntity, $channel);

            return self::mapToCardTeaser(
                trim((string)($entityData['title'] ?? '')),
                self::getUrl($entityData),
                self::getImageUrl($entityData),
                trim((string)($params['description'] ?? '')),
                trim((string)($entityData['subtitle'] ?? '')),
                ''
            );
        }

        return [];
    }

    public static function buildManualCardDefault(array $params): array
    {
        $title = trim((string)($params['title'] ?? ''));
        $imageSrc = trim((string)($params['image_src'] ?? ''));

        if ($title === '' || $imageSrc === '') {
            return [];
        }

        $data = [
            'title' => $title,
            'image' => [
                'src' => $imageSrc,
            ],
        ];

        $sectionTitle = trim((string)($params['section_title'] ?? ''));
        $sectionUrl = trim((string)($params['section_url'] ?? ''));
        if ($sectionTitle !== '' && $sectionUrl !== '') {
            $data['section'] = [
                'title' => $sectionTitle,
                'url' => $sectionUrl,
            ];
        }

        $description = trim((string)($params['description_manual'] ?? $params['description'] ?? ''));
        if ($description !== '') {
            $data['description'] = $description;
        }

        $linkUrl = trim((string)($params['link_url'] ?? ''));
        if ($linkUrl !== '') {
            $data['link'] = [
                'url' => $linkUrl,
            ];
        }

        $html = (string)($params['html'] ?? '');
        if ($html !== '') {
            $data['html'] = $html;
        }

        return $data;
    }

    private static function loadEntityDataByKey(
        string $entityType,
        int $idEntity,
        \CoreExtension\OutputChannelEnum $channel
    ): array {
        $entityTypeEnum = \CoreExtension\EntityReferenceParser::resolveEntityType($entityType);
        if (!$entityTypeEnum instanceof \CoreExtension\EntityTypeEnum || $idEntity <= 0) {
            return [];
        }

        $data = \CoreExtension\EntityDataRegistry::getData(
            new \CoreExtension\EntityReference($entityTypeEnum, $idEntity),
            $channel,
            \CoreExtension\EntityDataProfileEnum::FULL,
            ['channel' => $channel->value]
        );

        return is_array($data) ? $data : [];
    }

    private static function loadEntityData(
        \CoreExtension\EntityTypeEnum $entityType,
        int $idEntity,
        \CoreExtension\OutputChannelEnum $channel
    ): array {
        $data = \CoreExtension\EntityDataRegistry::getData(
            new \CoreExtension\EntityReference($entityType, $idEntity),
            $channel
        );

        return is_array($data) ? $data : [];
    }

    private static function getProductUrl(array $product): string
    {
        return trim((string)($product['product_url'] ?? self::getUrl($product)));
    }

    private static function getProductImageUrl(array $product): string
    {
        return self::getImageUrl($product);
    }

    private static function getUrl(array $data): string
    {
        if (isset($data['link']) && is_array($data['link'])) {
            return trim((string)($data['link']['url'] ?? ''));
        }

        if (isset($data['link']) && is_string($data['link'])) {
            return trim($data['link']);
        }

        return trim((string)($data['url'] ?? $data['product_url'] ?? ''));
    }

    private static function getImageUrl(array $data): string
    {
        return trim((string)($data['img'] ?? $data['image_url'] ?? $data['image']['src'] ?? ''));
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

    private static function mapToCardTeaser(
        string $title,
        string $url,
        string $imageSrc,
        string $description,
        string $shortDescription,
        string $price
    ): array {
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
            'description' => $description,
            'short_description' => $shortDescription,
            'price' => $price,
        ];
    }

    private static function resolveProductCategorySection(array $product): array
    {
        $idCategory = (int)($product['id_category_default'] ?? 0);
        if ($idCategory <= 0) {
            return [];
        }

        $entityData = self::loadEntityData(\CoreExtension\EntityTypeEnum::CATEGORY, $idCategory, \CoreExtension\OutputChannelEnum::WEB);
        $title = trim((string)($entityData['title'] ?? ''));
        $url = self::getUrl($entityData);
        if ($title === '' || $url === '') {
            return [];
        }

        return [
            'title' => $title,
            'url' => $url,
        ];
    }

    private static function normalizeEntityType(string $entityType): string
    {
        return strtolower(trim($entityType));
    }
}
