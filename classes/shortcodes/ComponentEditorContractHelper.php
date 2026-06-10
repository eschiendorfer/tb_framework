<?php

final class ComponentEditorContractHelper
{
    public static function styleField(ComponentDefinition $component, string $label = 'Style'): array
    {
        return \ShortcodeModule\EditorContractHelper::field(
            'style',
            $label,
            \CoreExtension\FormFieldTypeEnum::SELECT,
            false,
            'style',
            [
                'role' => \ShortcodeModule\ShortcodeFieldRoleEnum::PRESENTATION_STYLE->value,
                'options' => self::styleOptions($component),
                'default' => $component->getDefaultStyle(),
            ]
        );
    }

    private static function styleOptions(ComponentDefinition $component): array
    {
        return array_map(
            static fn(string $style): array => [
                'label' => $style,
                'value' => $style,
            ],
            $component->getStyles()
        );
    }
}
