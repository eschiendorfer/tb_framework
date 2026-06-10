<?php

class CardProductShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'card_product';
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
            \ShortcodeModule\EditorContractHelper::field('id_entity', 'Produkt-ID', \CoreExtension\FormFieldTypeEnum::TEXT, true, 'id_entity', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::ENTITY_ID->value,
                'placeholder' => 'Produkt-ID',
            ]),
            ComponentEditorContractHelper::styleField(new CardProductComponent()),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY,
                [\CoreExtension\EntityTypeEnum::PRODUCT],
                [],
                ['id_entity', 'style']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $componentChannel = self::resolveOutputChannel($channel);
        $idEntity = (int)($params['id_entity'] ?? 0);
        if ($idEntity <= 0) {
            return '';
        }

        $data = CardComponentDataMapper::resolveCardProduct(
            CardComponentDataMapper::ENTITY_TYPE_PRODUCT,
            $idEntity,
            $componentChannel
        );
        if (empty($data)) {
            return '';
        }

        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return trim((string)($data['name'] ?? ''));
        }

        $style = trim((string)($params['style'] ?? ''));
        if ($componentChannel === \CoreExtension\OutputChannelEnum::EMAIL) {
            return CardProductComponent::fetchEmail($data, $style);
        }

        return CardProductComponent::fetchWeb($data, $style);
    }

    private static function resolveOutputChannel(string $runtimeChannel): \CoreExtension\OutputChannelEnum
    {
        return \CoreExtension\OutputChannelEnum::tryFrom(strtolower(trim($runtimeChannel)))
            ?? \CoreExtension\OutputChannelEnum::WEB;
    }

}
