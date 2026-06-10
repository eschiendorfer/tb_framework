<?php

class AccordionDefaultShortcode implements \ShortcodeModule\ShortcodeInterface, \ShortcodeModule\ShortcodeEditorUploadInterface
{
    public static function getName(): string
    {
        return 'accordion_default';
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
            \ShortcodeModule\EditorContractHelper::field('json', 'JSON Datei', \CoreExtension\FormFieldTypeEnum::SELECT, true, 'json', [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::JSON_FILE->value,
                'options' => ComponentJsonDataLoader::getJsonFileOptionsForComponentName(self::getName()),
            ]),
            ComponentEditorContractHelper::styleField(new AccordionDefaultComponent()),
        ];
    }

    public static function getUsageDefinitions(): array
    {
        return [
            \ShortcodeModule\ShortcodeUsageDefinition::component(
                self::getName(),
                self::getName(),
                \ShortcodeModule\ShortcodeDataInputModeEnum::JSON,
                [],
                [],
                ['json', 'style']
            ),
        ];
    }

    public static function supportsEditorUpload(string $fieldKey): bool
    {
        return strtolower(trim($fieldKey)) === 'json';
    }

    public static function handleEditorUpload(string $fieldKey, array $file): array
    {
        if (!self::supportsEditorUpload($fieldKey)) {
            throw new InvalidArgumentException('Dieses Feld unterstuetzt keinen Upload.');
        }

        $fileName = ComponentJsonDataLoader::saveUploadedFile(new AccordionDefaultComponent(), $file);

        return [
            'value' => $fileName,
            'label' => $fileName,
            'options' => ComponentJsonDataLoader::getJsonFileOptionsForComponentName(self::getName()),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputModeEnum::HTML->value);
        if ($outputMode === \ShortcodeModule\ShortcodeOutputModeEnum::TEXT->value) {
            return '';
        }

        $component = new AccordionDefaultComponent();
        $data = ComponentJsonDataLoader::loadFromParams($params, $component);
        if (empty($data)) {
            return '';
        }

        return AccordionDefaultComponent::fetchWeb($data, trim((string)($params['style'] ?? '')));
    }
}
