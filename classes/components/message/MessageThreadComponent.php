<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class MessageThreadComponent extends ComponentDefinition {
    protected const TYPE = 'message';
    protected const NAME = 'message_thread';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        // Todo: add functionality that transform date into '3 weeks ago'

        return [
            'messages' => [
                [
                    'id_customer' => 0,
                    'id_message' => 0,
                    'alias'  => 'Frank',
                    'date'   => '2022-10-18 21:12:32',
                    'avatar' => 'https://images.unsplash.com/photo-1517070208541-6ddc4d3efbcb?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80',
                    'message' => 'Hello guys, how are you doing?',
                    'buttons' => '',
                    'respondings' => [],
                    'html' => '',
                ],
                [
                    'id_customer' => 0,
                    'id_message' => 0,
                    'alias'  => 'Melissa G.',
                    'date'   => '2022-10-19 05:06:51',
                    'avatar' => 'https://images.unsplash.com/photo-1581624657276-5807462d0a3a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80',
                    'message' => 'Doing great, thank you',
                    'buttons' => '',
                    'respondings' => [],
                    'html' => '',
                ],
                [
                    'id_customer' => 0,
                    'id_message' => 0,
                    'alias'  => 'King Arthur',
                    'date'   => '2022-10-19 09:14:01',
                    'avatar' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80',
                    'message' => 'It\'s monday. What a stupid question...',
                    'buttons' => '',
                    'respondings' => [],
                    'html' => '',
                ]
            ],
            'container' => [
                'boxed' => true,
                'margin' => true,
            ]
        ];
    }
}



