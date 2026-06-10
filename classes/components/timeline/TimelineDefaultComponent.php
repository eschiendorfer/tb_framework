<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class TimelineDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'timeline';
    protected const NAME = 'timeline_default';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'items' => [
                [
                    'date' => FrameworkDateFormatter::formatDateToTimeElapsed('2023-04-04'),
                    'html' => '<p><b>You</b> placed the order</p>',
                    'image' => ['']
                ],
                [
                    'date' => FrameworkDateFormatter::formatDateToTimeElapsed('2023-04-04'),
                    'html' => '<p><b>Emanuel</b> created the invoice.</p>',
                    'image' => ['']
                ],
                [
                    // 'date' => '2023-10-04',
                    'html' => '<div class="flex-auto rounded p-3 border bg-white">
                                    <div class="flex justify-between gap-x-4">
                                        <div class="py-0.5 text-xs leading-5 text-gray-500"><span class="font-medium text-gray-900">Chelsea Hagon</span> commented</div>
                                        <div class="flex-none py-0.5 text-xs leading-5 text-gray-500">'.FrameworkDateFormatter::formatDateToTimeElapsed('2024-04-04').'</div>
                                    </div>
                                    <p class="text-sm leading-6 text-gray-500">Called client, they reassured me the invoice would be paid by the 25th.</p>
                                </div>',
                    'image' => ['src' => _THEME_DIR_ . 'img/logos/spielezar-crown.webp']
                ],
                [
                    'date' => FrameworkDateFormatter::formatDateToTimeElapsed('2025-05-06'),
                    'html' => '<p><b>Emanuel</b> created the invoice.</p>',
                    'image' => ['']
                ],
            ],
        ];
    }
}



