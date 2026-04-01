<?php

class ImagecloudcompactShortcode implements \ShortcodeModule\ShortcodeInterface
{
    public static function getName(): string
    {
        return 'imagecloudcompact';
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
        return \ShortcodeModule\ShortcodeType::PARAMETER;
    }

    public static function getAllowedContext(): \ShortcodeModule\ShortcodeContext
    {
        return \ShortcodeModule\ShortcodeContext::ALL;
    }

    public static function getEditorAvailability(): array
    {
        return \ShortcodeModule\EditorContractHelper::availability('Imagecloudcompact', self::getAllowedChannels());
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
            \ShortcodeModule\EditorContractHelper::field('manufacturers', 'Hersteller IDs (CSV)', \CoreExtension\FormFieldTypeEnum::TEXT, true, 'manufacturers', [
                'placeholder' => 'z.B. 12,24,48',
            ]),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputMode::HTML->value);
        $context = Context::getContext();
        $idLang = $context->language->id;

        $items = [];
        $textItems = [];

        if (!empty($params['manufacturers'])) {
            $idsManufacturer = array_filter(array_map('trim', explode(',', $params['manufacturers'])));

            foreach ($idsManufacturer as $idManufacturer) {
                $idManufacturer = (int)$idManufacturer;
                if ($idManufacturer <= 0) {
                    continue;
                }

                $manufacturer = new Manufacturer($idManufacturer, $idLang);

                if (!$manufacturer->active) {
                    continue;
                }

                $url = $context->link->getManufacturerLink($manufacturer);
                $title = $manufacturer->name ?? '';
                $image = $context->link->getGenericImageLink('manufacturers', $idManufacturer, 'home_default');

                $items[] = [
                    'title' => $title,
                    'link' => [
                        'url' => $url,
                    ],
                    'image' => [
                        'src' => $image,
                    ],
                ];

                $textItems[] = $title . ' (' . $url . ')';
            }
        }

        if ($outputMode === \ShortcodeModule\ShortcodeOutputMode::TEXT->value) {
            return implode(', ', $textItems);
        }

        if (empty($items)) {
            return '';
        }

        $data = [
            'items' => $items,
        ];

        return ImagecloudCompactComponent::fetchWeb($data);
    }
}
