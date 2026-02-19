<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class CardSimpleComponent extends ComponentDefinition {
    protected const TYPE = 'card';
    protected const NAME = 'card_simple';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'rounded'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'default' => 'component/card/web/card_simple.tpl',
            'rounded' => 'component/card/web/card_simple/card_simple_rounded.tpl',
        ],
    ];

    public function validate(array &$data): void
    {
    }

    public function getDemoData(): array
    {
        return [
            'title'       => 'Sozialer als soziale Netzwerke',
            'image'       => [
                'src' => '/themes/genzo_theme/img/demo/cover.png',
            ],
            'button'      => [
                'title' => 'Familienspiele ansehen',
                'link'  => 'https://www.spielezar.ch/familienspiele',
            ]
        ];
    }
}



