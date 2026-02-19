<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class MenuHorizontalComponent extends ComponentDefinition {
    protected const TYPE = 'menu';
    protected const NAME = 'menu_horizontal';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'title' => 'Kategorien',
            'items' => [
                ['title' => 'Brettspiele', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-boardgame', 'width' => '20', 'height' => '20']],
                ['title' => 'Puzzle', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-puzzle']],
                ['title' => 'Trading Cards', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-tcg']],
                ['title' => 'Merchandise', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-actionfigure']],
                ['title' => 'Kinderspiele', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-childgame']],
                ['title' => 'Kartenspiele', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-cardgame']],
                ['title' => 'Würfelspiele', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-dicegame']],
                ['title' => 'Partyspiele', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-partygame']],
                ['title' => 'Reisespiele', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-travelgame']],
                ['title' => 'Abstrakte Spiele', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-abstractgame']],
                ['title' => 'Spiel des Jahres', 'link' => ['url' => '#'], 'icon' => ['class' => 'icon-spiel-des-jahres']],
            ],
        ];
    }
}
