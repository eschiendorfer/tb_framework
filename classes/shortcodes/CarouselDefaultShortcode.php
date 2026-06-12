<?php

class CarouselDefaultShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'carousel_default';
    }

    public static function getAllowedChannels(): array
    {
        return [
            \CoreExtension\OutputChannelEnum::WEB->value,
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
                'placeholder' => 'product:82,manufacturer:39',
            ]),
            \ShortcodeModule\EditorContractHelper::field('source', 'Dynamische Quelle (optional)', \CoreExtension\FormFieldTypeEnum::SELECT, false, 'source', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE->value,
                'default' => '',
                'options' => FrameworkEntityCollectionLoader::getCollectionSourceOptions(),
            ]),
            \ShortcodeModule\EditorContractHelper::field('source_id', 'Quell-ID', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'source_id', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE_ID->value,
                'visible_if' => [
                    'field' => 'source',
                    'operator' => 'in',
                    'value' => FrameworkEntityCollectionLoader::getCollectionSourceKeysRequiringSourceId(),
                ],
                'required_if' => [
                    'field' => 'source',
                    'operator' => 'in',
                    'value' => FrameworkEntityCollectionLoader::getCollectionSourceKeysRequiringSourceId(),
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
            \ShortcodeModule\EditorContractHelper::field('limit', 'Limit (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'limit', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::LIMIT->value,
            ]),
            \ShortcodeModule\EditorContractHelper::field('nbr_columns', 'Anzahl Spalten (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'nbr_columns'),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION,
                self::getSupportedItemEntityTypes(),
                [],
                ['items', 'limit', 'nbr_columns']
            ),
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION,
                FrameworkEntityCollectionLoader::getCollectionTargetEntityTypes(),
                FrameworkEntityCollectionLoader::getCollectionSourceKeys(),
                ['source', 'source_id', 'sort', 'limit', 'nbr_columns']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $rows = FrameworkEntityCollectionLoader::loadDataRows(
            $params,
            $channel,
            $context,
            self::getSupportedItemEntityTypes(),
            \CoreExtension\EntityDataProfileEnum::FULL
        );

        if (empty($rows)) {
            return '';
        }

        $slides = [];
        foreach ($rows as $row) {
            $slide = $this->renderRowAsSlide($row);
            if ($slide !== '') {
                $slides[] = $slide;
            }
        }

        if (empty($slides)) {
            return '';
        }

        return CarouselDefaultComponent::fetchWeb([
            'column_width' => '250px',
            'nbr_columns' => $params['nbr_columns'] ?? 2.5,
            'slides' => $slides,
        ]);
    }

    private function renderRowAsSlide(array $row): string
    {
        if (self::isProductRow($row)) {
            return CardProductComponent::fetchWeb($row);
        }

        $data = self::mapRowToCardDefault($row);
        if (empty($data)) {
            return '';
        }

        return CardDefaultComponent::fetchWeb($data);
    }

    private static function mapRowToCardDefault(array $row): array
    {
        $title = trim((string)($row['title'] ?? $row['name'] ?? ''));
        $image = self::resolveImage($row);

        if ($title === '' || $image === '') {
            return [];
        }

        $data = [
            'title' => $title,
            'image' => [
                'src' => $image,
            ],
        ];

        $description = trim((string)($row['subtitle'] ?? $row['description_short'] ?? $row['description'] ?? ''));
        if ($description !== '') {
            $data['description'] = $description;
        }

        $url = self::resolveUrl($row);
        if ($url !== '') {
            $data['link'] = [
                'url' => $url,
            ];
        }

        return $data;
    }

    private static function isProductRow(array $row): bool
    {
        if ((int)($row['entity_type'] ?? 0) === \CoreExtension\EntityTypeEnum::PRODUCT->value) {
            return true;
        }

        return strtolower(trim((string)($row['entity_type_key'] ?? ''))) === \CoreExtension\EntityTypeEnum::PRODUCT->getEntityTypeKey();
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

    private static function resolveImage(array $row): string
    {
        return trim((string)($row['img'] ?? $row['image_url'] ?? $row['image']['src'] ?? ''));
    }

    private static function getSupportedItemEntityTypes(): array
    {
        return [
            \CoreExtension\EntityTypeEnum::PRODUCT,
            \CoreExtension\EntityTypeEnum::CATEGORY,
            \CoreExtension\EntityTypeEnum::MANUFACTURER,
            \CoreExtension\EntityTypeEnum::CMS,
            \CoreExtension\EntityTypeEnum::BLOG,
            \CoreExtension\EntityTypeEnum::COMMUNITY_EVENT,
            \CoreExtension\EntityTypeEnum::CUSTOMPRODUCTLIST,
        ];
    }
}
