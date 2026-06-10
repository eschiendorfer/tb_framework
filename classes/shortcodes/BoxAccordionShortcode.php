<?php

class BoxAccordionShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'box_accordion';
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
        return \ShortcodeModule\ShortcodeTypeEnum::CONTENTBLOCK;
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
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title', [
                'default' => 'Quellenverzeichnis',
            ]),
            \ShortcodeModule\EditorContractHelper::field('icon', 'Icon', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'icon'),
            \ShortcodeModule\EditorContractHelper::field('height', 'Höhe', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'height', [
                'default' => '150px',
            ]),
            \ShortcodeModule\EditorContractHelper::field('content', 'Inhalt', \CoreExtension\FormFieldTypeEnum::TEXTAREA, true, null, [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::SHORTCODE_BODY->value,
                'default' => 'Hier ist custom stuff',
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
                ['content', 'title', 'icon', 'height']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $content = (string)($context['content'] ?? '');
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return trim(strip_tags($content));
        }

        return BoxAccordionComponent::fetchWeb([
            'title' => trim((string)($params['title'] ?? 'Quellenverzeichnis')),
            'icon' => trim((string)($params['icon'] ?? '')),
            'content' => $content,
            'height' => $params['height'] ?? '150px',
        ]);
    }
}
