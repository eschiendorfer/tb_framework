<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__, 2).'/components/imagecloud/ImagecloudAvatarComponent.php');

class MessagePreviewComponent extends ComponentDefinition {
    protected const TYPE = 'message';
    protected const NAME = 'message_preview';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        // Todo: add functionality that transform date into '3 weeks ago'

        // Todo: this shows how difficult it is to build complex components, how could we have a hover effect on avaters for genzo_krona module?
        // Atm it's probably the best, to give a shit. Go online and see how things work out for us. Later we still could look for general approach for tb

        $imagecloudAvatar = new ImagecloudAvatarComponent();

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
            'imagecloud_avatar' => $imagecloudAvatar->getDemoData(),
            'date' => '2023-01-03 23:12:01'
        ];
    }
}



