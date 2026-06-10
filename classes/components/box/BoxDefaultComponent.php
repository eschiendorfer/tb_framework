<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class BoxDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'box';
    protected const NAME = 'box_default';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (!isset($data['content']) || trim((string)$data['content']) === '') {
            throw new PrestaShopException('BoxDefaultComponent requires the "content" parameter.');
        }
    }

    public function getDemoData(): array {
        return [
            'icon' => 'icon icon-information-circle',
            'title' => 'Hinweis',
            'content' => '
                <p class="mb-0">Dies ist eine einfache Box ohne Flyout-Verhalten.</p>
            ',
        ];
    }
}
