<?php

class ProductgridShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'productgrid';
    }

    public static function getAllowedChannels(): array
    {
        return [
            ComponentChannel::EMAIL->value,
        ];
    }

    public static function getCacheKeyEnum(): ?\CoreExtension\CacheKeysEnum
    {
        return null;
    }

    public static function isAjaxOnly(): bool
    {
        return false;
    }

    public static function getShortcodeType(): \ShortcodeModule\ShortcodeType
    {
        return \ShortcodeModule\ShortcodeType::PARAMETER;
    }

    public static function getAllowedContext(): \ShortcodeModule\ShortcodeContext
    {
        return \ShortcodeModule\ShortcodeContext::ALL;
    }

    public static function getEditorAvailability(): array
    {
        return \ShortcodeModule\EditorContractHelper::availability('Productgrid', self::getAllowedChannels());
    }

    public static function getEditorRender(): array
    {
        return \ShortcodeModule\EditorContractHelper::render(\ShortcodeModule\ShortcodeRenderTypeEnum::FRAMEWORK_COMPONENT);
    }

    public static function getEditorEntity(): array
    {
        return \ShortcodeModule\EditorContractHelper::entityDisabled();
    }

    public static function getEditorFields(): array
    {
        return [
            \ShortcodeModule\EditorContractHelper::field('source_type', 'Produktquelle', \CoreExtension\FormFieldTypeEnum::SELECT, true, 'source_type', [
                'default' => EntityDataLoader::SOURCE_TYPE_CATEGORY,
                'options' => EntityDataLoader::getProductSourceTypeOptions(),
            ]),
            \ShortcodeModule\EditorContractHelper::field('id_entity', 'Quell-ID', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'id_entity', [
                'visible_if' => [
                    'field' => 'source_type',
                    'operator' => 'in',
                    'value' => [
                        EntityDataLoader::SOURCE_TYPE_CATEGORY,
                        EntityDataLoader::SOURCE_TYPE_MANUFACTURER,
                    ],
                ],
                'required_if' => [
                    'field' => 'source_type',
                    'operator' => 'in',
                    'value' => [
                        EntityDataLoader::SOURCE_TYPE_CATEGORY,
                        EntityDataLoader::SOURCE_TYPE_MANUFACTURER,
                    ],
                ],
                'placeholder' => 'ID',
            ]),
            \ShortcodeModule\EditorContractHelper::field('products', 'Produkt IDs (CSV)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'products', [
                'visible_if' => ['field' => 'source_type', 'operator' => 'equals', 'value' => EntityDataLoader::SOURCE_TYPE_PRODUCTS],
                'required_if' => ['field' => 'source_type', 'operator' => 'equals', 'value' => EntityDataLoader::SOURCE_TYPE_PRODUCTS],
                'placeholder' => 'z.B. 12,45,88',
            ]),
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title'),
            \ShortcodeModule\EditorContractHelper::field('limit', 'Limit (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'limit'),
            \ShortcodeModule\EditorContractHelper::field('nbr_columns', 'Anzahl Spalten (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'nbr_columns'),
            \ShortcodeModule\EditorContractHelper::field('button_title', 'Button Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'button_title'),
            \ShortcodeModule\EditorContractHelper::field('button_href', 'Button URL (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'button_href'),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $componentChannel = EntityDataLoader::resolveComponentChannel($channel);
        $products = EntityDataLoader::getProductDataByParams($params, $context, $componentChannel);

        if (empty($products)) {
            return '';
        }

        $contextObject = Context::getContext();
        $link = $contextObject->link ?? null;
        $normalizedProducts = [];

        foreach ($products as $product) {
            $normalizedProducts[] = $this->normalizeProductCardData($product, $link);
        }

        $button = [];
        $buttonTitle = trim((string)($params['button_title'] ?? ''));
        $buttonUrl = trim((string)($params['button_href'] ?? ($params['button_url'] ?? '')));
        if ($buttonTitle !== '') {
            $button = [
                'title' => $buttonTitle,
                'link' => ['url' => $buttonUrl],
            ];
        }

        return GridProductComponent::fetchEmail([
            'title' => $params['title'] ?? '',
            'button' => $button,
            'nbr_columns' => (int)($params['nbr_columns'] ?? 2),
            'products' => $normalizedProducts,
        ]);
    }

    private function normalizeProductCardData(array $product, $link): array
    {
        $productUrl = trim((string)($product['link'] ?? ''));
        if ($productUrl === '' && $link && !empty($product['id_product']) && !empty($product['link_rewrite'])) {
            $productUrl = (string)$link->getProductLink((int)$product['id_product'], (string)$product['link_rewrite']);
        }

        $imageUrl = '';
        if (!empty($product['img'])) {
            $imageUrl = (string)$product['img'];
        } elseif (!empty($product['cover']['bySize']['home_default']['url'])) {
            $imageUrl = (string)$product['cover']['bySize']['home_default']['url'];
        } elseif ($link && !empty($product['link_rewrite']) && !empty($product['id_image'])) {
            $imageUrl = (string)$link->getImageLink((string)$product['link_rewrite'], (string)$product['id_image'], 'home_default');
        }

        $price = isset($product['price']) ? (float)$product['price'] : 0.0;
        $priceWithoutReduction = isset($product['price_without_reduction']) ? (float)$product['price_without_reduction'] : $price;

        return [
            'name' => trim((string)($product['name'] ?? '')),
            'price' => $price,
            'reduction' => isset($product['reduction']) ? (float)$product['reduction'] : 0.0,
            'price_without_reduction' => $priceWithoutReduction,
            'product_url' => $productUrl,
            'image_url' => $imageUrl,
        ];
    }
}
