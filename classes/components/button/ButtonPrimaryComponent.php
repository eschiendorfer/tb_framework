<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ButtonPrimaryComponent extends ComponentDefinition {
    protected const TYPE = 'button';
    protected const NAME = 'button_primary';
    protected const CHANNELS = [
        \CoreExtension\OutputChannelEnum::WEB,
        \CoreExtension\OutputChannelEnum::EMAIL,
    ];
    protected const STYLES = ['default', 'small', 'large'];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        $data['href'] = trim((string)($data['href'] ?? ''));
        $data['title'] = trim((string)($data['title'] ?? ''));
        $data['target'] = trim((string)($data['target'] ?? ''));

        if ($data['title'] === '' && $data['href'] !== '') {
            $data['title'] = $data['href'];
        }
    }

    public function getDemoData(): array {
        return [
            'title' => 'Test',
            'href' => '#test'
        ];
    }
}



