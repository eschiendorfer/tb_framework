<?php

class CardTeaserShortcode implements \ShortcodeModule\ShortcodeInterface, \ShortcodeModule\CustomerContentEditShortcodeInterface
{
    public static function getName(): string
    {
        return 'card_teaser';
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
        return \ShortcodeModule\EditorContractHelper::entityConfig([
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::PRODUCT, 'Produkt', 'entity_type', false, ''),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::BLOG, 'Blog', 'entity_type', false, ''),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::MANUFACTURER, 'Hersteller', 'entity_type', false, ''),
        ]);
    }

    public static function getEditorFields(): array
    {
        return [
            \ShortcodeModule\EditorContractHelper::field('id_entity', 'ID / Wert', \CoreExtension\FormFieldTypeEnum::TEXT, true, 'id_entity', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::ENTITY_ID->value,
                'placeholder' => 'ID / Wert',
            ]),
            \ShortcodeModule\EditorContractHelper::field('description', 'Beschreibung', \CoreExtension\FormFieldTypeEnum::TEXTAREA, false, 'description'),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY,
                [
                    \CoreExtension\EntityTypeEnum::PRODUCT,
                    \CoreExtension\EntityTypeEnum::BLOG,
                    \CoreExtension\EntityTypeEnum::MANUFACTURER,
                ],
                [],
                ['entity_type', 'id_entity', 'description']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $componentChannel = self::resolveOutputChannel($channel);
        $entityType = strtolower(trim((string)($params['entity_type'] ?? '')));
        $data = CardComponentDataMapper::resolveCardTeaser(
            $entityType,
            (int)($params['id_entity'] ?? 0),
            $params,
            $componentChannel
        );
        if (empty($data)) {
            return '';
        }

        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::CUSTOMER_CONTENT_EDIT->value) {
            return $this->resolveCustomerContentEditUrl($params, $entityType, $data);
        }

        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            $title = trim((string)($data['title'] ?? ''));
            $url = trim((string)($data['link']['url'] ?? ''));

            return $url !== '' ? $title . ' (' . $url . ')' : $title;
        }

        if ($componentChannel === \CoreExtension\OutputChannelEnum::EMAIL) {
            return CardTeaserComponent::fetchEmail($data);
        }

        return CardTeaserComponent::fetchWeb($data);
    }

    public function renderCustomerContentEdit(array $params, string $channel, array $context = []): string
    {
        $entityType = strtolower(trim((string)($params['entity_type'] ?? '')));
        $data = CardComponentDataMapper::resolveCardTeaser(
            $entityType,
            (int)($params['id_entity'] ?? 0),
            $params,
            self::resolveOutputChannel($channel)
        );

        return empty($data) ? '' : $this->resolveCustomerContentEditUrl($params, $entityType, $data);
    }

    private function resolveCustomerContentEditUrl(array $params, string $entityType, array $data): string
    {
        if ($entityType !== CardComponentDataMapper::ENTITY_TYPE_PRODUCT || !$this->isAutomaticProductTeaser($params)) {
            return '';
        }

        $url = trim((string)($data['link']['url'] ?? ''));

        return $this->isCustomerContentUrl($url) ? $url : '';
    }

    private function isAutomaticProductTeaser(array $params): bool
    {
        $expectedKeys = ['entity_type' => true, 'id_entity' => true];
        foreach ($params as $key => $value) {
            if (!isset($expectedKeys[(string)$key])) {
                return false;
            }
        }

        return (int)($params['id_entity'] ?? 0) > 0;
    }

    private function isCustomerContentUrl(string $value): bool
    {
        return (bool)preg_match('#^https://[^\s<>"\']+$#i', $value);
    }

    private static function resolveOutputChannel(string $runtimeChannel): \CoreExtension\OutputChannelEnum
    {
        return \CoreExtension\OutputChannelEnum::tryFrom(strtolower(trim($runtimeChannel)))
            ?? \CoreExtension\OutputChannelEnum::WEB;
    }
}
