<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__, 2).'/mappers/ImagecloudAvatarComponentDataMapper.php');

class MessagePreviewComponent extends ComponentDefinition {
    protected const TYPE = 'message';
    protected const NAME = 'message_preview';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        // Todo: add functionality that transform date into '3 weeks ago'

        return [
            'cta_title' => [
                'title' => 'example title',
                'href'  => '/hihi'
            ],
            'cta_section' => [
                'title' => 'Puzzles',
                'href'  => '/jojo'
            ],
            'message' => 'Lorem ispum ...',
            'messages_total' => 3,
            'imagecloud_avatar' => [
                'images' => ImagecloudAvatarComponentDataMapper::map($this->getTeamCustomerDemoProfileRows(3)),
            ],
            'date' => '2023-01-03 23:12:01'
        ];
    }
}



