<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ReviewDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'review';
    protected const NAME = 'review_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $reviews_default = [
            [
                'id_customer' => 0,
                'id_review' => 0,
                'customer' => [
                    'name' => 'Genzo Wakabayashi',
                    'image' => ['src' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80'],
                    'link' => ['href' => ''],
                ],
                'review_grade' => rand(1*10,5*10)/10,
                'review_date' => date('d. F Y'),
                'review_title' => 'Something',
                'review_content' => '<p>Mauris non odio at est convallis rhoncus at vitae odio. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut sagittis, nibh sit amet porttitor efficitur, purus urna elementum dolor, in congue ipsum augue scelerisque ex.</p>',
                'rich_snippets' => false,
                'verified_buyer' => true,
            ],
            [
                'id_customer' => 0,
                'id_review' => 0,
                'customer' => [
                    'name' => 'Martina Meyer',
                    'image' => ['src' => 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80'],
                    'link' => ['href' => ''],
                ],
                'review_grade' => rand(1*10,5*10)/10,
                'review_date' => date('d. F Y', strtotime('-3 months -2 days')),
                'review_title' => 'Something',
                'review_content' => '<p>Nullam placerat luctus odio, sed tincidunt ex volutpat sed. Maecenas at magna nec mi vulputate egestas eget non nibh. </p>',
                'rich_snippets' => false,
                'verified_buyer' => false,
            ],
            [
                'id_customer' => 0,
                'id_review' => 0,
                'customer' => [
                    'name' => 'Sadio Perreira',
                    'image' => ['src' => 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixqx=oilqXxSqey&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'],
                    'link' => ['href' => ''],
                ],
                'review_grade' => rand(1*10,5*10)/10,
                'review_date' => date('d. F Y', strtotime('-1 year -2 weeks')),
                'review_title' => 'Something',
                'review_content' => '<p>In hac habitasse platea dictumst. Nunc volutpat neque vitae nunc condimentum, placerat elementum ex gravida. In eu ligula sodales, egestas nunc id, porttitor lorem. Vestibulum pretium risus eu turpis bibendum vehicula. Morbi vestibulum tellus non tortor molestie, sit amet maximus leo mattis.</p><p>Morbi facilisis ipsum quis odio efficitur egestas sit amet ac quam. Fusce sodales ex sem. Nunc at sapien auctor, dapibus ipsum at, varius purus. Aenean egestas enim in lorem porttitor pulvinar. Quisque suscipit lobortis enim vitae rutrum. Quisque a neque dolor. Curabitur non sodales lectus.</p>',
                'rich_snippets' => false,
                'verified_buyer' => true,
            ],
        ];

        $key = rand(1, count($reviews_default)) - 1;
        return $reviews_default[$key];
    }
}



