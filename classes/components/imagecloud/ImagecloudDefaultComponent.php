<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ImagecloudDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_default';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'compact'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'default' => 'component/imagecloud/web/imagecloud_default.tpl',
            'compact' => 'component/imagecloud/web/imagecloud_default/imagecloud_default_compact.tpl',
        ],
    ];

    public function validate(array &$data): void {
        if (!isset($data['items']) || !is_array($data['items'])) {
            $data['items'] = [];
        }
    }

    public function getDemoData(): array {
        $items = [
            [
                'title' => 'Boardgames',
                'image' => ['src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg'],
                'link' => ['url' => '#'],
            ],
            [
                'title' => 'Card Games',
                'image' => ['src' => _THEME_DIR_.'/img/icons/colored/cardgame.svg'],
                'link' => ['url' => '#'],
            ],
            [
                'title' => 'Collectibles',
                'image' => ['src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg'],
                'link' => ['url' => '#'],
            ],
            [
                'title' => 'Chess',
                'image' => ['src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg'],
                'link' => ['url' => '#'],
            ],
        ];

        return [
            'title' => 'Handcrafted Brands',
            'items' => $items,
            'button' => [
                'title' => 'View All Brands',
                'link'  => ['url' => '#'],
                'style' => 'width: 100%;',
            ],
        ];
    }
}
