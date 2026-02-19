<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class MessageChatComponent extends ComponentDefinition {
    protected const TYPE = 'message';
    protected const NAME = 'message_chat';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'compact'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'compact' => 'component/message/web/message_chat/message_chat_compact.tpl',
        ],
    ];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        // Todo: add functionality that transform date into '3 weeks ago'

        return [
            'title' => 'Some Title',
            'alias' => 'Frank the tank',
            'avatar' => 'https://images.unsplash.com/photo-1517070208541-6ddc4d3efbcb?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80',
            'messages' => [
                [
                    'date'   => '2022-10-18 21:12:32',
                    'own_message' => false,
                    'message' => 'Hello guys, how are you doing?',
                    'buttons' => ''
                ],
                [
                    'date'   => '2022-10-18 21:12:32',
                    'own_message' => true,
                    'message' => 'Doing great, thank you',
                    'buttons' => ''
                ],
                [
                    'date'   => '2022-10-18 21:12:32',
                    'own_message' => true,
                    'message' => 'What about you?',
                    'buttons' => ''
                ],
            ],
            'container' => [
                'boxed' => true,
                'margin' => true,
            ]
        ];
    }
}



