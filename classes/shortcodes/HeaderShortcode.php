<?php

class HeaderShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'header';
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
        return \ShortcodeModule\EditorContractHelper::availability('Header', self::getAllowedChannels());
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

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputMode::HTML->value);
        $title = trim((string)($params['title'] ?? ''));
        $subtitle = trim((string)($params['subtitle'] ?? ''));
        $description = trim((string)($params['description'] ?? ''));
        $linkUrl = trim((string)($params['link_url'] ?? ''));
        $linkTitle = trim((string)($params['link_title'] ?? ''));

        if ($outputMode === \ShortcodeModule\ShortcodeOutputMode::TEXT->value) {
            $parts = [];
            if ($subtitle !== '') {
                $parts[] = $subtitle;
            }
            if ($title !== '') {
                $parts[] = $title;
            }
            if ($description !== '') {
                $parts[] = $description;
            }
            if ($linkTitle !== '' && $linkUrl !== '') {
                $parts[] = $linkTitle . ' (' . $linkUrl . ')';
            } elseif ($linkUrl !== '') {
                $parts[] = $linkUrl;
            }

            return implode("\n", $parts);
        }

        $data = [];

        if ($title !== '') {
            $data['title'] = $title;
        }
        if ($subtitle !== '') {
            $data['subtitle'] = $subtitle;
        }
        if ($description !== '') {
            $data['description'] = $description;
        }
        if ($linkUrl !== '' && $linkTitle !== '') {
            $data['link'] = [
                'url' => $linkUrl,
                'title' => $linkTitle,
            ];
        }

        if ($channel === ComponentChannel::EMAIL->value) {
            return HeaderDefaultComponent::fetchEmail($data);
        }

        return HeaderDefaultComponent::fetchWeb($data);
    }
}
