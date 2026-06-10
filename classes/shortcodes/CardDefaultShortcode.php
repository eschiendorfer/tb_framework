<?php

class CardDefaultShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'card_default';
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
        $entity = \ShortcodeModule\EditorContractHelper::entityConfig([
            \ShortcodeModule\EditorContractHelper::entityTypeCustom(CardComponentDataMapper::ENTITY_TYPE_MANUAL, 'Manuell', 'entity_type', false, ''),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::PRODUCT, 'Produkt', 'entity_type', false, ''),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::CATEGORY, 'Kategorie', 'entity_type', false, ''),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::MANUFACTURER, 'Hersteller', 'entity_type', false, ''),
        ]);

        return $entity;
    }

    public static function getEditorFields(): array
    {
        return [
            \ShortcodeModule\EditorContractHelper::field('id_entity', 'ID / Wert', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'id_entity', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::ENTITY_ID->value,
                'visible_if' => [
                    'field' => 'entity_type',
                    'operator' => 'in',
                    'value' => [
                        CardComponentDataMapper::ENTITY_TYPE_PRODUCT,
                        CardComponentDataMapper::ENTITY_TYPE_CATEGORY,
                        CardComponentDataMapper::ENTITY_TYPE_MANUFACTURER,
                    ],
                ],
                'required_if' => [
                    'field' => 'entity_type',
                    'operator' => 'in',
                    'value' => [
                        CardComponentDataMapper::ENTITY_TYPE_PRODUCT,
                        CardComponentDataMapper::ENTITY_TYPE_CATEGORY,
                        CardComponentDataMapper::ENTITY_TYPE_MANUFACTURER,
                    ],
                ],
                'placeholder' => 'ID / Wert',
            ]),
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
                'required_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
            ]),
            \ShortcodeModule\EditorContractHelper::field('section_title', 'Sektion Titel', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'section_title', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
            ]),
            \ShortcodeModule\EditorContractHelper::field('section_url', 'Sektion URL', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'section_url', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
                'placeholder' => '/blog/example',
            ]),
            \ShortcodeModule\EditorContractHelper::field('description_manual', 'Beschreibung', \CoreExtension\FormFieldTypeEnum::TEXTAREA, false, 'description_manual', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
            ]),
            \ShortcodeModule\EditorContractHelper::field('image_src', 'Bild URL', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'image_src', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
                'required_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
                'placeholder' => 'https://...',
            ]),
            \ShortcodeModule\EditorContractHelper::field('link_url', 'Link URL', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'link_url', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
                'placeholder' => '/blog/example',
            ]),
            \ShortcodeModule\EditorContractHelper::field('html', 'HTML', \CoreExtension\FormFieldTypeEnum::TEXTAREA, false, 'html', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => CardComponentDataMapper::ENTITY_TYPE_MANUAL],
            ]),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::MANUAL,
                [],
                [],
                ['entity_type', 'title', 'section_title', 'section_url', 'description_manual', 'image_src', 'link_url', 'html']
            ),
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY,
                [
                    \CoreExtension\EntityTypeEnum::PRODUCT,
                    \CoreExtension\EntityTypeEnum::CATEGORY,
                    \CoreExtension\EntityTypeEnum::MANUFACTURER,
                ],
                [],
                ['entity_type', 'id_entity']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $entityType = strtolower(trim((string)($params['entity_type'] ?? CardComponentDataMapper::ENTITY_TYPE_MANUAL)));
        $data = $entityType === CardComponentDataMapper::ENTITY_TYPE_MANUAL
            ? CardComponentDataMapper::buildManualCardDefault($params)
            : CardComponentDataMapper::resolveCardDefault(
                $entityType,
                (int)($params['id_entity'] ?? 0),
                \CoreExtension\OutputChannelEnum::WEB
            );

        if (empty($data)) {
            return '';
        }

        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return trim((string)($data['title'] ?? ''));
        }

        return CardDefaultComponent::fetchWeb($data);
    }
}
