<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class MenuVerticalComponent extends ComponentDefinition {
    protected const TYPE = 'menu';
    protected const NAME = 'menu_vertical';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $items = [
            [
                'title' => 'Brettspiele',
                'link' => ['url' => '/test'],
                'icon' => ['class' => 'icon-boardgame', 'width' => '20', 'height' => '20'],
                'active' => false
            ],
            [
                'title' => 'Puzzle',
                'link' => ['url' => '#'],
                'icon' => ['class' => 'icon-puzzle', 'width' => '20', 'height' => '20'],
                'active' => false
            ],
            [
                'title' => 'TCG',
                'link' => ['url' => '#'],
                'icon' => ['class' => 'icon-tcg', 'width' => '20', 'height' => '20'],
                'active' => false
            ],
            [
                'title' => 'Merchandise',
                'link' => ['url' => '#'],
                'icon' => ['class' => 'icon-actionfigure', 'width' => '20', 'height' => '20'],
                'active' => false
            ],

            /*['title' => 'Kinderspiele', 'url' => '#', 'icon' => ['class' => 'icon-childgame']],
            ['title' => 'Kartenspiele', 'url' => '#', 'icon' => ['class' => 'icon-cardgame']],
            ['title' => 'Würfelspiele', 'url' => '#', 'icon' => ['class' => 'icon-dicegame']],
            ['title' => 'Partyspiele', 'url' => '#', 'icon' => ['class' => 'icon-partygame']],
            ['title' => 'Reisespiele', 'url' => '#', 'icon' => ['class' => 'icon-travelgame']],
            ['title' => 'Abstrakte Spiele', 'url' => '#', 'icon' => ['class' => 'icon-abstractgame']],
            ['title' => 'Spiel des Jahres', 'url' => '#', 'icon' => ['class' => 'icon-spiel-des-jahres']],*/
        ];

        return [
            'title' => 'Kategorien',
            'items' => $items,
        ];
    }
}
