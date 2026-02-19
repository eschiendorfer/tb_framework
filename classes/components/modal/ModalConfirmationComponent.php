<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ModalConfirmationComponent extends ComponentDefinition {
    protected const TYPE = 'modal';
    protected const NAME = 'modal_confirmation';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (empty($data['width'])) {
            $data['width'] = '600px';
        }

        if (!isset($data['triggers_show']) || !is_array($data['triggers_show'])) {
            $data['triggers_show'] = ['click_item'];
        }

        if (!isset($data['triggers_close']) || !is_array($data['triggers_close'])) {
            $data['triggers_close'] = ['click_close_button'];
        }

        $this->applyDefaultTriggers($data);
    }

    public function getDemoData(): array {
        $html = '<h2>Are you sure?</h2><p>Do you really want to delete your account?</p>';

        return [
            'html' => $html,
            'title_action' => 'Delete',
            'title' => 'Custom Modal',
            'width' => '',
            'height' => '',
            'item' => '',
            'triggers_show' => ['click_item'],
            'triggers_close' => ['click_close_button'],
            'link' => ['href' => "#", 'title' => 'Some Link Title'],
        ];
    }

    private function applyDefaultTriggers(array &$data): void {
        if (!isset($data['triggers_show']) || !is_array($data['triggers_show'])) {
            $data['triggers_show'] = ['auto_show', 'click_item'];
        }

        if (!isset($data['triggers_close']) || !is_array($data['triggers_close'])) {
            $data['triggers_close'] = ['click_close_button', 'click_item', 'click_outside'];
        }
    }
}



