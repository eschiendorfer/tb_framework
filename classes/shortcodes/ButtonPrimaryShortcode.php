<?php

class ButtonPrimaryShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'button_primary';
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
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::PRODUCT, 'Produkt', '', true, 'Produkt-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::CATEGORY, 'Kategorie', '', true, 'Kategorie-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::CMS, 'CMS', '', true, 'CMS-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::BLOG, 'Blog', '', true, 'Blog-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::COMMUNITY_EVENT, 'Event', '', true, 'Event-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::MANUFACTURER, 'Hersteller', '', true, 'Hersteller-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::CUSTOMPRODUCTLIST, 'Custom Product List', '', true, 'List-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeCustom('external', 'Externer Link', 'external', true, 'https://...'),
        ]);
    }

    public static function getEditorFields(): array
    {
        return [
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title'),
            ComponentEditorContractHelper::styleField(new ButtonPrimaryComponent()),
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
                ['external', 'href', 'title', 'style']
            ),
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY,
                [
                    \CoreExtension\EntityTypeEnum::PRODUCT,
                    \CoreExtension\EntityTypeEnum::CATEGORY,
                    \CoreExtension\EntityTypeEnum::CMS,
                    \CoreExtension\EntityTypeEnum::BLOG,
                    \CoreExtension\EntityTypeEnum::COMMUNITY_EVENT,
                    \CoreExtension\EntityTypeEnum::MANUFACTURER,
                    \CoreExtension\EntityTypeEnum::CUSTOMPRODUCTLIST,
                ],
                [],
                ['product', 'category', 'cms', 'blog', 'community_event', 'manufacturer', 'customproductlist', 'title', 'style']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        $linkInformation = \ShortcodeModule\EntityLinkResolver::resolve($params, $context);

        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return $linkInformation['active'] ? $linkInformation['href'] : '';
        }

        if (!$linkInformation['active']) {
            return '';
        }

        if (trim((string)($linkInformation['title'] ?? '')) === '' && trim((string)($linkInformation['href'] ?? '')) !== '') {
            $linkInformation['title'] = trim((string)$linkInformation['href']);
        }

        if (trim((string)($linkInformation['href'] ?? '')) === '' || trim((string)($linkInformation['title'] ?? '')) === '') {
            return '';
        }

        $style = trim((string)($params['style'] ?? ''));

        if ($channel === \CoreExtension\OutputChannelEnum::EMAIL->value) {
            return ButtonPrimaryComponent::fetchEmail($linkInformation, $style);
        }

        return ButtonPrimaryComponent::fetchWeb($linkInformation, $style);
    }

}
