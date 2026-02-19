<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CardTeaserComponent extends ComponentDefinition {
    protected const TYPE = 'card';
    protected const NAME = 'card_teaser';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        return [
            'title'       => 'Sozialer als soziale Netzwerke',
            'image'       => [
                'src' => '/themes/genzo_theme/img/demo/cover.png',
            ],
            'description'  => 'Nam posuere ac lacus id convallis. Nunc ac enim leo. Suspendisse dolor nunc, rhoncus at eros vitae, dictum euismod eros. Suspendisse eu aliquam libero, a volutpat dolor. Morbi commodo nec velit eu facilisis. Fusce eget faucibus nisl.',
        ];
    }
}



