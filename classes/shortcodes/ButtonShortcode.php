<?php

class ButtonShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'button';
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
        return \ShortcodeModule\EditorContractHelper::availability('Button', self::getAllowedChannels());
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
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::MANUFACTURER, 'Hersteller', '', true, 'Hersteller-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(\CoreExtension\EntityTypeEnum::CUSTOM_PRODUCT_LIST, 'Custom Product List', '', true, 'List-ID'),
            \ShortcodeModule\EditorContractHelper::entityTypeCustom('external', 'Externer Link', 'external', true, 'https://...'),
        ]);
    }

    public static function getEditorFields(): array
    {
        return [
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title'),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputMode::HTML->value);
        $linkInformation = EntityDataLoader::getLinkData($params);

        if ($outputMode === \ShortcodeModule\ShortcodeOutputMode::TEXT->value) {
            return $linkInformation['active'] ? $linkInformation['href'] : '';
        }

        if ($channel === ComponentChannel::EMAIL->value) {
            return ButtonPrimaryComponent::fetchEmail($linkInformation);
        }

        $buttonPrimary = ButtonPrimaryComponent::fetchCssClasses();

        if ($linkInformation['active']) {
            return '<div style="text-align: center; margin: 20px 0 10px 0;"><a href="' . $linkInformation['href'] . '" class="' . $buttonPrimary . '">' . $linkInformation['title'] . '</a></div>';
        }

        return '';
    }
}
