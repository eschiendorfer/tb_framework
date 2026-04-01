<?php

class BoxShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'box';
    }

    public static function getAllowedChannels(): array
    {
        return [
            ComponentChannel::WEB->value,
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
        return \ShortcodeModule\ShortcodeType::CONTENTBLOCK;
    }

    public static function getAllowedContext(): \ShortcodeModule\ShortcodeContext
    {
        return \ShortcodeModule\ShortcodeContext::ALL;
    }

    public static function getEditorAvailability(): array
    {
        return \ShortcodeModule\EditorContractHelper::availability('Box', self::getAllowedChannels());
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
            \ShortcodeModule\EditorContractHelper::field('style', 'Box Style', \CoreExtension\FormFieldTypeEnum::SELECT, true, 'style', [
                'default' => 'sources',
                'options' => [
                    ['label' => 'Default', 'value' => 'default'],
                    ['label' => 'Quellenverzeichnis', 'value' => 'sources'],
                    ['label' => 'Infobox', 'value' => 'info'],
                    ['label' => 'Tippbox', 'value' => 'tipp'],
                ],
            ]),
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title', [
                'visible_if' => ['field' => 'style', 'operator' => 'equals', 'value' => 'default'],
            ]),
            \ShortcodeModule\EditorContractHelper::field('icon', 'Icon', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'icon', [
                'visible_if' => ['field' => 'style', 'operator' => 'equals', 'value' => 'default'],
            ]),
            \ShortcodeModule\EditorContractHelper::field('height', 'Höhe', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'height', [
                'default' => '150px',
                'visible_if' => ['field' => 'style', 'operator' => 'not_equals', 'value' => 'default'],
            ]),
            \ShortcodeModule\EditorContractHelper::field('content', 'Inhalt', \CoreExtension\FormFieldTypeEnum::TEXTAREA, true, null, [
                'role' => 'content',
                'default' => 'Hier ist custom stuff',
            ]),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputMode::HTML->value);
        $content = $context['content'] ?? '';
        if ($outputMode === \ShortcodeModule\ShortcodeOutputMode::TEXT->value) {
            return trim(strip_tags($content));
        }

        $style = $params['style'] ?? 'sources';
        if ($style === 'default') {
            $data = [
                'content' => $content,
            ];

            if (!empty($params['title'])) {
                $data['title'] = $params['title'];
            }

            if (!empty($params['icon'])) {
                $data['icon'] = $params['icon'];
            }

            $component = FrameworkRegistry::getByName('box_default');
            if (!$component) {
                return '';
            }

            return $component::fetchWeb($data);
        }

        $title = 'Quellenverzeichnis';
        if ($style === 'info') {
            $title = 'Infobox';
        } elseif ($style === 'tipp') {
            $title = 'Tippbox';
        }

        $data = [
            'title' => $title,
            'icon' => '',
            'content' => $content,
            'height' => $params['height'] ?? '150px',
        ];

        $component = FrameworkRegistry::getByName('box_accordion');
        if (!$component) {
            return '';
        }

        return $component::fetchWeb($data);
    }
}
