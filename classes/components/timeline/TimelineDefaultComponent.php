<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class TimelineDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'timeline';
    protected const NAME = 'timeline_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'items' => [
                [
                    'date' => '2023-04-04',
                    'html' => '<p><b>You</b> placed the order</p>',
                    'image' => ['']
                ],
                [
                    'date' => '2023-04-04',
                    'html' => '<p><b>Emanuel</b> created the invoice.</p>',
                    'image' => ['']
                ],
                [
                    // 'date' => '2023-10-04',
                    'html' => '<div class="flex-auto rounded p-3 border bg-white">
                                    <div class="flex justify-between gap-x-4">
                                        <div class="py-0.5 text-xs leading-5 text-gray-500"><span class="font-medium text-gray-900">Chelsea Hagon</span> commented</div>
                                        <time datetime="2023-01-23T15:56" class="flex-none py-0.5 text-xs leading-5 text-gray-500">3d ago</time>
                                    </div>
                                    <p class="text-sm leading-6 text-gray-500">Called client, they reassured me the invoice would be paid by the 25th.</p>
                                </div>',
                    'image' => ['src' => 'https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80']
                ],
                [
                    'date' => '2023-05-06',
                    'html' => '<p><b>Emanuel</b> created the invoice.</p>',
                    'image' => ['']
                ],
            ],
        ];
    }
}



