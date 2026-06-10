<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class StatsBarChartHorizontalComponent extends ComponentDefinition {
    protected const TYPE = 'stats';
    protected const NAME = 'stats_bar_chart_horizontal';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'title' => 'Top Spieler',
            'subtitle' => 'Ausgewählte Weltmeister',
            'labels' => ['Magnus Carlsen', 'Garry Kasparov', 'Bobby Fischer', 'Anatoly Karpov', 'Vishy Anand'],
            'values' => [2882, 2851, 2785, 2780, 2788],
            'tooltip' => 'Elo: $value',
            'width' => '100%',
        ];
    }
}
