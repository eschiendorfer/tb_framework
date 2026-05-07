<?php

final class ContextMenuActionBuilder
{
    /**
     * @param array<string, scalar|null> $attributes
     * @return array<string, string>
     */
    public static function buildAjaxContextAction(
        string $label,
        string $href = '#',
        string $iconClass = '',
        array $attributes = []
    ): array {
        $action = [
            'label' => $label,
            'href' => $href,
            'data_ajax' => 'true',
        ];

        if ($iconClass !== '') {
            $action['icon_class'] = $iconClass;
        }

        foreach ($attributes as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (is_string($value) && $value === '') {
                continue;
            }

            $action[$key] = (string)$value;
        }

        return $action;
    }
}
