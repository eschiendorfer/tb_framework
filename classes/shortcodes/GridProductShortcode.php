<?php

class GridProductShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'grid_product';
    }

    public static function getAllowedChannels(): array
    {
        return [
            \CoreExtension\OutputChannelEnum::EMAIL->value,
        ];
    }

    public static function getCacheKeyEnum(): ?\CoreExtension\CacheKeysEnum
    {
        return null;
    }

    public static function getShortcodeType(): \ShortcodeModule\ShortcodeTypeEnum
    {
        return \ShortcodeModule\ShortcodeTypeEnum::PARAMETER;
    }

    public static function getAllowedContext(): \ShortcodeModule\ShortcodeContextEnum
    {
        return \ShortcodeModule\ShortcodeContextEnum::ALL;
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
            \ShortcodeModule\EditorContractHelper::field('items', 'Manuelle Items (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'items', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_ITEMS->value,
                'placeholder' => 'product:82,product:93',
            ]),
            \ShortcodeModule\EditorContractHelper::field('source', 'Dynamische Quelle (optional)', \CoreExtension\FormFieldTypeEnum::SELECT, false, 'source', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE->value,
                'default' => '',
                'options' => FrameworkEntityCollectionLoader::getCollectionSourceOptions(\CoreExtension\EntityTypeEnum::PRODUCT),
            ]),
            \ShortcodeModule\EditorContractHelper::field('source_id', 'Quell-ID', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'source_id', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE_ID->value,
                'visible_if' => [
                    'field' => 'source',
                    'operator' => 'in',
                    'value' => FrameworkEntityCollectionLoader::getCollectionSourceKeysRequiringSourceId(\CoreExtension\EntityTypeEnum::PRODUCT),
                ],
                'required_if' => [
                    'field' => 'source',
                    'operator' => 'in',
                    'value' => FrameworkEntityCollectionLoader::getCollectionSourceKeysRequiringSourceId(\CoreExtension\EntityTypeEnum::PRODUCT),
                ],
                'placeholder' => 'ID',
            ]),
            \ShortcodeModule\EditorContractHelper::field('sort', 'Sortierung (optional)', \CoreExtension\FormFieldTypeEnum::SELECT, false, 'sort', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SORT->value,
                'default' => '',
                'options' => FrameworkEntityCollectionLoader::getCollectionSortOptions(\CoreExtension\EntityTypeEnum::PRODUCT),
                'visible_if' => [
                    'field' => 'source',
                    'operator' => 'in',
                    'value' => FrameworkEntityCollectionLoader::getCollectionSourceKeysSupportingSort(\CoreExtension\EntityTypeEnum::PRODUCT),
                ],
            ]),
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title'),
            \ShortcodeModule\EditorContractHelper::field('limit', 'Limit (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'limit', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::LIMIT->value,
            ]),
            \ShortcodeModule\EditorContractHelper::field('nbr_columns', 'Anzahl Spalten (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'nbr_columns'),
            \ShortcodeModule\EditorContractHelper::field('image_type', 'Bildtyp (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'image_type', [
                'placeholder' => 'home_default',
            ]),
            \ShortcodeModule\EditorContractHelper::field('button_title', 'Button Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'button_title'),
            \ShortcodeModule\EditorContractHelper::field('button_href', 'Button URL (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'button_href'),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION,
                [\CoreExtension\EntityTypeEnum::PRODUCT],
                [],
                ['items', 'title', 'limit', 'nbr_columns', 'image_type', 'button_title', 'button_href']
            ),
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION,
                [\CoreExtension\EntityTypeEnum::PRODUCT],
                FrameworkEntityCollectionLoader::getCollectionSourceKeys(\CoreExtension\EntityTypeEnum::PRODUCT),
                ['source', 'source_id', 'sort', 'title', 'limit', 'nbr_columns', 'image_type', 'button_title', 'button_href']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $imageType = trim((string)($params['image_type'] ?? 'home_default'));
        $products = \CoreExtension\ProductEntityDataProvider::loadProductsFromReferences(
            FrameworkEntityCollectionLoader::loadReferences(
                $params,
                $channel,
                $context,
                [\CoreExtension\EntityTypeEnum::PRODUCT]
            ),
            \CoreExtension\OutputChannelEnum::EMAIL,
            null,
            $imageType !== '' ? ['image_type' => $imageType] : []
        );

        if (empty($products)) {
            return '';
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
            'products' => $products,
        ]);
    }
}
