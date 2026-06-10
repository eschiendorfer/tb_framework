<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__).'/imagecloud/ImagecloudAvatarComponent.php');

class MessageThreadComponent extends ComponentDefinition {
    protected const TYPE = 'message';
    protected const NAME = 'message_thread';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'compact'];

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        // Todo: add functionality that transform date into '3 weeks ago'
        $profiles = ImagecloudAvatarComponent::getKronaAvatarProfiles(3);
        $firstProfile = $profiles[0] ?? [];
        $secondProfile = $profiles[1] ?? [];
        $thirdProfile = $profiles[2] ?? [];

        return [
            'messages' => [
                [
                    'id_customer' => (int)($firstProfile['id_customer'] ?? 0),
                    'id_message' => 0,
                    'alias'  => (string)($firstProfile['name'] ?? 'Frank'),
                    'date'   => '2022-10-18 21:12:32',
                    'avatar' => (string)($firstProfile['avatar'] ?? ''),
                    'message' => 'Hello guys, how are you doing?',
                    'buttons' => '',
                    'respondings' => [],
                    'html' => '',
                ],
                [
                    'id_customer' => (int)($secondProfile['id_customer'] ?? 0),
                    'id_message' => 0,
                    'alias'  => (string)($secondProfile['name'] ?? 'Melissa G.'),
                    'date'   => '2022-10-19 05:06:51',
                    'avatar' => (string)($secondProfile['avatar'] ?? ''),
                    'message' => 'Doing great, thank you',
                    'buttons' => '',
                    'respondings' => [],
                    'html' => '',
                ],
                [
                    'id_customer' => (int)($thirdProfile['id_customer'] ?? 0),
                    'id_message' => 0,
                    'alias'  => (string)($thirdProfile['name'] ?? 'King Arthur'),
                    'date'   => '2022-10-19 09:14:01',
                    'avatar' => (string)($thirdProfile['avatar'] ?? ''),
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



