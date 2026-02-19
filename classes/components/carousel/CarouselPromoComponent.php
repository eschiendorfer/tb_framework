<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CarouselPromoComponent extends ComponentDefinition {
    protected const TYPE = 'carousel';
    protected const NAME = 'carousel_promo';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $demo_data_carousel = (new CarouselComponentsComponent())->getDemoData();

        $demo_data_carousel['nbr_columns'] = 2.5;
        $demo_data_carousel['promo_position'] = 'left';
        $demo_data_carousel['title'] = 'Title';
        $demo_data_carousel['image'] = [
            'src' => 'https://img.welt.de/img/wirtschaft/mobile188192711/5862508847-ci102l-w1024/Spielehersteller-Ravensburger.jpg'
        ];
        $demo_data_carousel['link'] = [
            'href' => '/bla'
        ];

        return $demo_data_carousel;
    }
}



