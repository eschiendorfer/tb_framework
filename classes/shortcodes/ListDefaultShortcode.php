<?php

class ListDefaultShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'list_default';
    }

    public static function getAllowedChannels(): array
    {
        return [
            \CoreExtension\OutputChannelEnum::WEB->value,
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
                'placeholder' => 'product:82,community_event:10',
            ]),
            \ShortcodeModule\EditorContractHelper::field('include_inactive', 'Inaktive manuelle Produkte anzeigen', \CoreExtension\FormFieldTypeEnum::SELECT, false, 'include_inactive', [
                'default' => '0',
                'options' => [
                    ['label' => 'Nein', 'value' => '0'],
                    ['label' => 'Ja', 'value' => '1'],
                ],
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
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title'),
            \ShortcodeModule\EditorContractHelper::field('limit', 'Limit (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'limit', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::LIMIT->value,
            ]),
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
                ['items', 'title', 'limit', 'include_inactive']
            ),
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION,
                FrameworkEntityCollectionLoader::getCollectionTargetEntityTypes(),
                FrameworkEntityCollectionLoader::getCollectionSourceKeys(),
                ['source', 'source_id', 'sort', 'title', 'limit']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        $outputChannel = \CoreExtension\OutputChannelEnum::tryFrom(strtolower(trim($channel)))
            ?? \CoreExtension\OutputChannelEnum::WEB;
        $data = ListComponentDataMapper::map(
            FrameworkEntityCollectionLoader::loadDataRows(
                $params,
                $channel,
                $context,
                [],
                \CoreExtension\EntityDataProfileEnum::FULL
            ),
            (string)($params['title'] ?? ''),
            $outputChannel
        );

        if (empty($data)) {
            return '';
        }

        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return $this->renderRowsAsText($data['data']);
        }

        if ($channel === \CoreExtension\OutputChannelEnum::EMAIL->value) {
            return ListDefaultComponent::fetchEmail($data);
        }

        return ListDefaultComponent::fetchWeb($data);
    }

    private function renderRowsAsText(array $rows): string
    {
        $textList = [];
        foreach ($rows as $row) {
            $title = trim((string)($row['title'] ?? ''));
            $url = trim((string)($row['link']['url'] ?? ''));
            if ($title === '') {
                continue;
            }

            $textList[] = $url !== '' ? $title . ' (' . $url . ')' : $title;
        }

        return implode("\n", $textList);
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
