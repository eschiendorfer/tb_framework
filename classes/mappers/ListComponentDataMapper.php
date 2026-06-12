<?php

final class ListComponentDataMapper
{
    public static function map(
        array $entityRows,
        string $title = '',
        ?\CoreExtension\OutputChannelEnum $channel = null
    ): array
    {
        $channel ??= \CoreExtension\OutputChannelEnum::WEB;
        $rows = [];
        foreach ($entityRows as $row) {
            if (!is_array($row) || trim((string)($row['title'] ?? '')) === '') {
                continue;
            }

            $isProduct = self::isProductRow($row);
            $isInactiveProduct = $isProduct && !self::isActiveProduct($row);

            $rows[] = [
                'img' => self::resolveImage($row),
                'title' => $isProduct ? self::formatProductTitle($row) : trim((string)$row['title']),
                'subtitle' => $isProduct
                    ? self::formatProductSubtitle($row, $channel)
                    : trim((string)($row['subtitle'] ?? '')),
                'link' => [
                    'url' => $isInactiveProduct ? '' : self::resolveUrl($row),
                ],
            ];
        }

        if (empty($rows)) {
            return [];
        }

        return [
            'title' => trim($title),
            'data' => $rows,
        ];
    }

    private static function isProductRow(array $row): bool
    {
        if ((int)($row['entity_type'] ?? 0) === \CoreExtension\EntityTypeEnum::PRODUCT->value) {
            return true;
        }

        return strtolower(trim((string)($row['entity_type_key'] ?? ''))) === \CoreExtension\EntityTypeEnum::PRODUCT->getEntityTypeKey();
    }

    private static function formatProductTitle(array $row): string
    {
        $title = trim((string)($row['title'] ?? $row['name'] ?? ''));
        if (!self::isActiveProduct($row)) {
            return $title;
        }

        $price = trim((string)($row['price_text'] ?? ''));

        if ($title === '' || $price === '' || str_contains($title, $price)) {
            return $title;
        }

        return $title . ' (' . $price . ')';
    }

    private static function formatProductSubtitle(array $row, \CoreExtension\OutputChannelEnum $channel): string
    {
        $inactiveStatus = !self::isActiveProduct($row) ? self::renderInactiveProductStatus($channel) : '';

        if ($channel === \CoreExtension\OutputChannelEnum::WEB) {
            $featuresHtml = self::renderProductFeatures($row);
            if ($featuresHtml !== '') {
                return trim($featuresHtml . $inactiveStatus);
            }
        }

        return trim($inactiveStatus !== '' ? $inactiveStatus : (string)($row['subtitle'] ?? ''));
    }

    private static function isActiveProduct(array $row): bool
    {
        if (array_key_exists('is_active', $row)) {
            return (bool)$row['is_active'];
        }

        if (array_key_exists('active', $row)) {
            return (bool)$row['active'];
        }

        return true;
    }

    private static function renderInactiveProductStatus(\CoreExtension\OutputChannelEnum $channel): string
    {
        if ($channel === \CoreExtension\OutputChannelEnum::WEB) {
            $badgeClass = self::resolveCssTokenClass('badge_danger', 'small', '');
            return '<div class="mt-1"><span class="' . $badgeClass . '">Produkt nicht mehr verfügbar</span></div>';
        }

        return 'Produkt nicht mehr verfügbar';
    }

    private static function resolveCssTokenClass(string $tokenName, string $style, string $fallback): string
    {
        if (!class_exists('CssTokenRegistry')) {
            return $fallback;
        }

        $cssSelectors = CssTokenRegistry::getAllCssSelectors();
        $cssClass = trim((string)($cssSelectors[$tokenName][$style] ?? ''));

        return $cssClass !== '' ? $cssClass : $fallback;
    }

    private static function renderProductFeatures(array $row): string
    {
        $features = $row['features'] ?? [];
        if (empty($features) || !is_array($features) || !defined('_PS_THEME_DIR_')) {
            return '';
        }

        $templatePath = _PS_THEME_DIR_ . 'includes/product-list/product-list-features.tpl';
        if (!file_exists($templatePath)) {
            return '';
        }

        try {
            $smarty = \Context::getContext()->smarty;
            $smarty->assign([
                'component' => [
                    'id_product' => (int)($row['id_product'] ?? $row['id_entity'] ?? 0),
                ],
                'features' => $features,
                'css_style' => 'default',
                'manufacturer_name' => trim((string)($row['manufacturer_name'] ?? $row['manufacturer'] ?? '')),
                'id_category_default' => (int)($row['id_category_default'] ?? 0),
            ]);

            return trim((string)$smarty->fetch($templatePath));
        } catch (\Throwable) {
            return '';
        }
    }

    private static function resolveImage(array $row): string
    {
        return trim((string)($row['img'] ?? $row['image_url'] ?? $row['image']['src'] ?? ''));
    }

    private static function resolveUrl(array $row): string
    {
        if (isset($row['link']) && is_array($row['link'])) {
            return trim((string)($row['link']['url'] ?? ''));
        }

        if (isset($row['link']) && is_string($row['link'])) {
            return trim($row['link']);
        }

        return trim((string)($row['url'] ?? $row['product_url'] ?? ''));
    }
}
