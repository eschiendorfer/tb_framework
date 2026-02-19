<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class AccordionDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'accordion';
    protected const NAME = 'accordion_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'sections' => [
                [
                    'title' => 'Puzzles',
                    'items' => [
                        [
                            'title' => 'Können Einzelteile nachbestellt werden?',
                            'content' => 'aksdjfkjsdkf',
                        ],
                    ]
                ],
                [
                    'title' => 'Allgemein',
                    'items' => [
                        [
                            'title' => 'Wie schnell ist der Versand?',
                            'content' => 'aksdjfkjsdkf',
                        ],
                        [
                            'title' => 'Kann ich auf Rechnung kaufen?',
                            'content' => 'aksdjfkjsdkf',
                        ],
                    ]
                ],
            ],
        ];
    }
}
