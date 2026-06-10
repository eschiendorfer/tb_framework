<?php

class HeaderDefaultShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'header_default';
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
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title'),
            \ShortcodeModule\EditorContractHelper::field('subtitle', 'Subtitle (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'subtitle'),
            \ShortcodeModule\EditorContractHelper::field('description', 'Beschreibung (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'description'),
            \ShortcodeModule\EditorContractHelper::field('link_url', 'Link URL (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'link_url'),
            \ShortcodeModule\EditorContractHelper::field('link_title', 'Link Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'link_title'),
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
                ['title', 'subtitle', 'description', 'link_url', 'link_title']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $data = $this->buildData($params);
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return $this->buildTextOutput($data);
        }

        if ($channel === \CoreExtension\OutputChannelEnum::EMAIL->value) {
            return HeaderDefaultComponent::fetchEmail($data);
        }

        return HeaderDefaultComponent::fetchWeb($data);
    }

    private function buildData(array $params): array
    {
        $data = [];
        foreach (['title', 'subtitle', 'description'] as $key) {
            $value = trim((string)($params[$key] ?? ''));
            if ($value !== '') {
                $data[$key] = $value;
            }
        }

        $linkUrl = trim((string)($params['link_url'] ?? ''));
        $linkTitle = trim((string)($params['link_title'] ?? ''));
        if ($linkUrl !== '' && $linkTitle !== '') {
            $data['link'] = [
                'url' => $linkUrl,
                'title' => $linkTitle,
            ];
        }

        return $data;
    }

    private function buildTextOutput(array $data): string
    {
        $parts = [];
        foreach (['subtitle', 'title', 'description'] as $key) {
            if (!empty($data[$key])) {
                $parts[] = (string)$data[$key];
            }
        }

        $linkTitle = trim((string)($data['link']['title'] ?? ''));
        $linkUrl = trim((string)($data['link']['url'] ?? ''));
        if ($linkTitle !== '' && $linkUrl !== '') {
            $parts[] = $linkTitle . ' (' . $linkUrl . ')';
        } elseif ($linkUrl !== '') {
            $parts[] = $linkUrl;
        }

        return implode("\n", $parts);
    }
}
