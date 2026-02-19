<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CardPromoComponent extends ComponentDefinition {
    protected const TYPE = 'card';
    protected const NAME = 'card_promo';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'title' => 'Sozialer als soziale Netzwerke',
            'subtitle' => 'Familienspiele',
            'description' => 'Eltern können Kindern nichts Schöneres schenken als gemeinsame Zeit. Mit Brettspielen erlebt man lustige Momente, an die man sich noch Jahre später erinnert. Für einen Spielnachmittag reichen ein paar Snacks, leckere Getränke und ein tolles Spiel.',
            'image' => [
                'src' => '/themes/genzo_theme/img/demo/cover.png',
            ],
            'button' => [
                'title' => 'Familienspiele ansehen',
                'link' => 'https://www.spielezar.ch/familienspiele',
            ]
        ];
    }
}
