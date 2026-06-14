<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

use CoreExtension\EntityTypeEnum;

class ReviewSectionComponent extends ComponentDefinition {
    protected const TYPE = 'review';
    protected const NAME = 'review_section';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
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

        $review_stats = [
            'reviews_grade_aggregated' => round($stars_total/$reviews_count_total,2),
            'reviews_total_count' => $reviews_count_total,
            'stats' => $stats,
        ];

        $reviews = $this->getReviewDefaults();

        return [
            'entity_type' => EntityTypeEnum::PRODUCT->value,
            'entity_id' => 8,
            'reviews_grade_aggregated' => $review_stats['reviews_grade_aggregated'],
            'reviews_total_count' => $review_stats['reviews_total_count'],
            'stats' => $review_stats['stats'],
            'write_button_content' => '',
            'reviews' => [
                $reviews[0],
                $reviews[1],
                $reviews[2],
            ],
            'rich_snippets' => false,
        ];
    }

    private function getReviewDefaults(): array {
        $profiles = $this->getTeamCustomerDemoProfileRows(3);
        $firstProfile = $profiles[0] ?? [];
        $secondProfile = $profiles[1] ?? [];
        $thirdProfile = $profiles[2] ?? [];

        return [
            [
                'id_customer' => (int)($firstProfile['id_entity'] ?? 0),
                'id_review' => 0,
                'customer' => [
                    'name' => (string)($firstProfile['title'] ?? ''),
                    'image' => ['src' => (string)($firstProfile['avatar'] ?? '')],
                    'link' => ['href' => (string)($firstProfile['url'] ?? '')],
                ],
                'review_grade' => rand(1*10,5*10)/10,
                'review_date' => date('d. F Y'),
                'review_title' => 'Something',
                'review_content' => '<p>Mauris non odio at est convallis rhoncus at vitae odio. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut sagittis, nibh sit amet porttitor efficitur, purus urna elementum dolor, in congue ipsum augue scelerisque ex.</p>',
                'rich_snippets' => false,
                'verified_buyer' => true,
            ],
            [
                'id_customer' => (int)($secondProfile['id_entity'] ?? 0),
                'id_review' => 0,
                'customer' => [
                    'name' => (string)($secondProfile['title'] ?? ''),
                    'image' => ['src' => (string)($secondProfile['avatar'] ?? '')],
                    'link' => ['href' => (string)($secondProfile['url'] ?? '')],
                ],
                'review_grade' => rand(1*10,5*10)/10,
                'review_date' => date('d. F Y', strtotime('-3 months -2 days')),
                'review_title' => 'Something',
                'review_content' => '<p>Nullam placerat luctus odio, sed tincidunt ex volutpat sed. Maecenas at magna nec mi vulputate egestas eget non nibh. </p>',
                'rich_snippets' => false,
                'verified_buyer' => false,
            ],
            [
                'id_customer' => (int)($thirdProfile['id_entity'] ?? 0),
                'id_review' => 0,
                'customer' => [
                    'name' => (string)($thirdProfile['title'] ?? ''),
                    'image' => ['src' => (string)($thirdProfile['avatar'] ?? '')],
                    'link' => ['href' => (string)($thirdProfile['url'] ?? '')],
                ],
                'review_grade' => rand(1*10,5*10)/10,
                'review_date' => date('d. F Y', strtotime('-1 year -2 weeks')),
                'review_title' => 'Something',
                'review_content' => '<p>In hac habitasse platea dictumst. Nunc volutpat neque vitae nunc condimentum, placerat elementum ex gravida. In eu ligula sodales, egestas nunc id, porttitor lorem. Vestibulum pretium risus eu turpis bibendum vehicula. Morbi vestibulum tellus non tortor molestie, sit amet maximus leo mattis.</p><p>Morbi facilisis ipsum quis odio efficitur egestas sit amet ac quam. Fusce sodales ex sem. Nunc at sapien auctor, dapibus ipsum at, varius purus. Aenean egestas enim in lorem porttitor pulvinar. Quisque suscipit lobortis enim vitae rutrum. Quisque a neque dolor. Curabitur non sodales lectus.</p>',
                'rich_snippets' => false,
                'verified_buyer' => true,
            ],
        ];
    }
}



