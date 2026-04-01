<?php

class CarouselShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'carousel';
    }

    public static function getAllowedChannels(): array
    {
        return [
            ComponentChannel::WEB->value,
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
        return \ShortcodeModule\EditorContractHelper::availability('Carousel', self::getAllowedChannels());
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
            \ShortcodeModule\EditorContractHelper::field('limit', 'Limit (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'limit'),
            \ShortcodeModule\EditorContractHelper::field('nbr_columns', 'Anzahl Spalten (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'nbr_columns'),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $componentChannel = EntityDataLoader::resolveComponentChannel($channel);
        $products = EntityDataLoader::getProductDataByParams($params, $context, $componentChannel);

        if (empty($products)) {
            return '';
        }

        $productBoxes = [];

        foreach ($products as $product) {
            $productBoxes[] = CardProductComponent::fetchWeb($product);
        }

        $data = [
            'column_width' => '250px',
            'nbr_columns' => $params['nbr_columns'] ?? 2.5,
            'slides' => $productBoxes,
        ];

        return CarouselComponentsComponent::fetchWeb($data);
    }
}
