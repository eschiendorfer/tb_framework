<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class StatsLineChartComponent extends ComponentDefinition {
    protected const TYPE = 'stats';
    protected const NAME = 'stats_line_chart';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'title' => 'Peak Elo',
            'subtitle' => 'Top 5 Spieler',
            'labels' => ['2000', '2005', '2010', '2015', '2020'],
            'values' => [2780, 2795, 2815, 2840, 2860],
            'tooltip' => 'Elo: $value',
            'width' => '100%',
        ];
    }
}



