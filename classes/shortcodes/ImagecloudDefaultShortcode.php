<?php

class ImagecloudDefaultShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'imagecloud_default';
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
        return \ShortcodeModule\EditorContractHelper::entityDisabled();
    }

    public static function getEditorFields(): array
    {
        return [
            \ShortcodeModule\EditorContractHelper::field('items', 'Manuelle Items', \CoreExtension\FormFieldTypeEnum::TEXT, true, 'items', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_ITEMS->value,
                'placeholder' => 'manufacturer:39,manufacturer:57',
            ]),
            ComponentEditorContractHelper::styleField(new ImagecloudDefaultComponent()),
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title'),
            \ShortcodeModule\EditorContractHelper::field('button_title', 'Button Titel (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'button_title'),
            \ShortcodeModule\EditorContractHelper::field('button_href', 'Button URL (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'button_href'),
            \ShortcodeModule\EditorContractHelper::field('limit', 'Limit (optional)', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'limit', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::LIMIT->value,
            ]),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION,
                [\CoreExtension\EntityTypeEnum::MANUFACTURER],
                [],
                ['items', 'style', 'title', 'button_title', 'button_href', 'limit']
            ),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $style = trim((string)($params['style'] ?? ''));
        $items = ImagecloudComponentDataMapper::map(
            FrameworkEntityCollectionLoader::loadDataRows(
                $params,
                $channel,
                $context,
                [\CoreExtension\EntityTypeEnum::MANUFACTURER],
                \CoreExtension\EntityDataProfileEnum::FULL
            )
        );
        if (empty($items)) {
            return '';
        }

        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return $this->renderItemsAsText($items);
        }

        $data = [
            'items' => $items,
        ];

        $title = trim((string)($params['title'] ?? ''));
        if ($title !== '') {
            $data['title'] = $title;
        }

        $buttonTitle = trim((string)($params['button_title'] ?? ''));
        $buttonHref = trim((string)($params['button_href'] ?? ''));
        if ($buttonTitle !== '' && $buttonHref !== '') {
            $data['button'] = [
                'title' => $buttonTitle,
                'link' => ['url' => $buttonHref],
            ];
        }

        return ImagecloudDefaultComponent::fetchWeb($data, $style);
    }

    private function renderItemsAsText(array $items): string
    {
        $textItems = [];
        foreach ($items as $item) {
            $title = trim((string)($item['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $url = trim((string)($item['link']['url'] ?? ''));
            $textItems[] = $url !== '' ? $title . ' (' . $url . ')' : $title;
        }

        return implode(', ', $textItems);
    }
}
