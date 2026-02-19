<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ImagecloudDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        // Todo: how to know how many elements are showed? -> the theme needs to tell
        $manufacturers = Manufacturer::getManufacturers(false, 1, true, 1, 12);

        $data = [
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Boardgames',
                'link' => ['url' => '#'],
            ],
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Card Games',
                'link' => ['url' => '#'],
            ],
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Collectibles',
                'link' => ['url' => '#'],
            ],
            [
                'src' => _THEME_DIR_.'/img/icons/colored/boardgame.svg',
                'title' => 'Chess',
                'link' => ['url' => '#'],
            ],
        ];

        /*foreach ($manufacturers as $manufacturer) {
            $data[] = [
                'src' => __PS_BASE_URI__ . 'img/m/' . (int) $manufacturer['id_manufacturer'] . '.jpg',
                'title' => $manufacturer['name'],
                'link' => ['url' => $this->context->link->getManufacturerLink($manufacturer['id_manufacturer'])],
            ];
        }*/

        return [
            'title' => 'Handcrafted Brands',
            'data' => $data,
            'button' => [
                'title' => 'View All Brands',
                'link'  => ['url' => '#'],
                'style' => 'width: 100%;',
            ],
        ];
    }
}



