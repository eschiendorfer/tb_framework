<?php

class CardPromoShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'card_promo';
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
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(
                \CoreExtension\EntityTypeEnum::TEASER_BANNER,
                'Teaser Banner',
                'entity_type',
                false,
                ''
            ),
        ]);
    }

    public static function getEditorFields(): array
    {
        return [
            \ShortcodeModule\EditorContractHelper::field('id_entity', 'Teaser-Banner ID', \CoreExtension\FormFieldTypeEnum::TEXT, true, 'id_entity', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::ENTITY_ID->value,
                'placeholder' => 'Teaser-Banner ID',
            ]),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY,
                [\CoreExtension\EntityTypeEnum::TEASER_BANNER],
                [],
                ['entity_type', 'id_entity']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $componentChannel = self::resolveOutputChannel($channel);
        $data = CardPromoComponentDataMapper::resolveCardPromo(
            strtolower(trim((string)($params['entity_type'] ?? ''))),
            (int)($params['id_entity'] ?? 0),
            $componentChannel
        );

        if (empty($data)) {
            return '';
        }

        if ($componentChannel === \CoreExtension\OutputChannelEnum::EMAIL) {
            return CardPromoComponent::fetchEmail($data);
        }

        return CardPromoComponent::fetchWeb($data);
    }

    private static function resolveOutputChannel(string $runtimeChannel): \CoreExtension\OutputChannelEnum
    {
        return \CoreExtension\OutputChannelEnum::tryFrom(strtolower(trim($runtimeChannel)))
            ?? \CoreExtension\OutputChannelEnum::WEB;
    }
}
