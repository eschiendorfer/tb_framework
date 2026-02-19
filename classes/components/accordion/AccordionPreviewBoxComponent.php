<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class AccordionPreviewBoxComponent extends ComponentDefinition {
    protected const TYPE = 'accordion';
    protected const NAME = 'accordion_preview_box';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (empty($data['title'])) {
            $data['title'] = 'Preview Box';
        }

        if (!isset($data['content']) || $data['content'] === '') {
            $data['content'] = '<p>Kein Inhalt vorhanden.</p>';
        }

        if (empty($data['height'])) {
            $data['height'] = '140px';
        }
    }

    public function getDemoData(): array {
        return [
            'icon' => 'icon icon-information-circle',
            'title' => 'Was ist in der Box enthalten?',
            'height' => '140px',
            'content' => '
                <p class="mb-3">Diese Preview Box zeigt nur einen Ausschnitt des Inhalts.</p>
                <ul class="list-disc pl-5">
                    <li>Einleitung und kurze Zusammenfassung</li>
                    <li>Wichtige Kernpunkte auf einen Blick</li>
                    <li>Option zum Ein- und Ausblenden</li>
                    <li>Zeile 4</li>
                    <li>Zeile 5</li>
                    <li>Zeile 6</li>
                </ul>
            ',
        ];
    }
}
