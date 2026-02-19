<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ImagecloudAvatarComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_avatar';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $images = [
            [
                'image' => [
                    'imageEntity' => '',
                    'idEntity' => 0,
                    'src' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80',
                    'width' => 0,
                    'height' => 0,
                    'alt' => 'Something Alt',
                ],
                'id' => '', // Can be used for javascript stuff
                'link' => [
                    'href' => '/demo/avatar1',
                    'title' => '',
                ]
            ],
            [
                'image' => [
                    'imageEntity' => '',
                    'idEntity' => 0,
                    'src' => 'https://images.unsplash.com/photo-1581624657276-5807462d0a3a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80',
                    'width' => 0,
                    'height' => 0,
                    'alt' => 'Something Alt',
                ],
                'id' => '', // Can be used for javascript stuff
                'link' => [
                    'href' => 'demo/avatar2',
                    'title' => '',
                ]
            ],
            [
                'image' => [
                    'imageEntity' => '',
                    'idEntity' => 0,
                    'src' => 'https://images.unsplash.com/photo-1517070208541-6ddc4d3efbcb?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80',
                    'width' => 0,
                    'height' => 0,
                    'alt' => 'Something Alt',
                ],
                'id' => '', // Can be used for javascript stuff
                'link' => [
                    'href' => 'demo/avatar2',
                    'title' => '',
                ]
            ],
        ];

        return [
            'images' => $images,
        ];
    }
}



