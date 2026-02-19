<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CardDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'card';
    protected const NAME = 'card_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'title' => 'Sozialer als alle sozialen Netzwerke',
            'section' => [
                'title' => 'Familienspiele',
                'url' => '#'
            ],
            'description' => 'Eltern können ihren Kindern nichts Schöneres schenken als gemeinsame Zeit. Brettspiele schweissen zusammen und man erlebt lustige Momente, an die man sich noch Jahre später erinnert.',
            'image' => [
                'src' => '/themes/genzo_theme/img/demo/cover.png',
            ],
            'link' => [
                'url' => '/blog/something',
            ],
            'html' => '',
        ];
    }
}
