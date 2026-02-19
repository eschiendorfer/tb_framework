<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ReviewStatsComponent extends ComponentDefinition {
    protected const TYPE = 'review';
    protected const NAME = 'review_stats';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $stats = [
            5 => rand(0,100),
            4 => rand(0,200),
            3 => rand(0,50),
            2 => rand(0,50),
            1 => rand(0,100),
        ];

        $reviews_count_total = array_sum($stats);
        $stars_total = 0;

        foreach ($stats as $star => $count) {
            $stars_total+= $star*$count;
        }

        return [
            'reviews_grade_aggregated' => round($stars_total/$reviews_count_total,2),
            'reviews_total_count' => $reviews_count_total,
            'stats' => $stats,
            'rich_snippets' => false,
        ];
    }
}



