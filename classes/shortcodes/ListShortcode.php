<?php

class ListShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'list';
    }

    public static function getAllowedChannels(): array
    {
        return [
            ComponentChannel::WEB->value,
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
        return \ShortcodeModule\EditorContractHelper::availability('List', self::getAllowedChannels());
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
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputMode::HTML->value);
        $componentChannel = EntityDataLoader::resolveComponentChannel($channel);
        $products = EntityDataLoader::getProductDataByParams($params, $context, $componentChannel);

        if (empty($products)) {
            return '';
        }

        if ($outputMode === \ShortcodeModule\ShortcodeOutputMode::TEXT->value) {
            $textList = [];
            foreach ($products as $product) {
                $textList[] = trim((string)($product['name'] ?? '')) . ' (' . trim((string)($product['link'] ?? '')) . ')';
            }

            return implode("\n", $textList);
        }

        $data = [
            'title' => $params['title'] ?? '',
            'data' => [],
        ];

        $shopContext = Context::getContext();

        foreach ($products as $product) {
            $shopContext->smarty->assign([
                'features' => $product['features'] ?? [],
                'css_style' => 'bla',
                'manufacturer_name' => (string)($product['manufacturer_name'] ?? ''),
                'id_category_default' => (int)($product['id_category_default'] ?? 0),
            ]);

            $features = $shopContext->smarty->fetch(_PS_THEME_DIR_ . 'includes/product-list/product-list-features.tpl');
            $product['features_html'] = $features;

            $imageUrl = trim((string)($product['img'] ?? ''));
            if ($imageUrl === '') {
                $imageUrl = (string)$shopContext->link->getImageLink(
                    (string)($product['link_rewrite'] ?? ''),
                    (string)($product['id_image'] ?? ''),
                    'small_default'
                );
            }

            $data['data'][] = [
                'img' => $imageUrl,
                'title' => EntityDataLoader::getTitleForProduct($product, true),
                'subtitle' => EntityDataLoader::getSubtitleForProduct($product),
                'link' => ['url' => (string)($product['link'] ?? '')],
                'element_columns' => [],
            ];
        }

        if ($channel === ComponentChannel::EMAIL->value) {
            return ListCompactComponent::fetchEmail($data);
        }

        return ListCompactComponent::fetchWeb($data);
    }
}
