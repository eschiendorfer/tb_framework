<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__).'/imagecloud/ImagecloudAvatarComponent.php');

class MessageChatComponent extends ComponentDefinition {
    protected const TYPE = 'message';
    protected const NAME = 'message_chat';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
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
        $profile = ImagecloudAvatarComponent::getKronaAvatarProfiles(1)[0] ?? [];

        return [
            'title' => 'Some Title',
            'alias' => (string)($profile['name'] ?? 'Frank the tank'),
            'avatar' => (string)($profile['avatar'] ?? ''),
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



