<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ModalDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'modal';
    protected const NAME = 'modal_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        $this->applyDefaultTriggers($data);
    }

    public function getDemoData(): array {
        $html = '<br><br>This is a custom modal';

        $html .= HeaderDefaultComponent::fetchDemo();
        $html .= CarouselComponentsComponent::fetchDemo();

        return [
            'html' => $html,
            'title' => 'Custom Modal',
            'width' => '',
            'height' => '',
            'item' => '',
            'triggers_show' => ['auto_show', 'click_item'],
            'triggers_close' => ['click_close_button', 'click_item', 'click_outside'],
            'callback_open' => '',
            'callback_close' => '',
        ];
    }

    protected function applyDefaultTriggers(array &$data): void {
        if (!isset($data['triggers_show']) || !is_array($data['triggers_show'])) {
            $data['triggers_show'] = ['auto_show', 'click_item'];
        }

        if (!isset($data['triggers_close']) || !is_array($data['triggers_close'])) {
            $data['triggers_close'] = ['click_close_button', 'click_item', 'click_outside'];
        }
    }
}



