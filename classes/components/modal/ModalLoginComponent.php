<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ModalLoginComponent extends ComponentDefinition {
    protected const TYPE = 'modal';
    protected const NAME = 'modal_login';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'checkout'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'default' => 'component/modal/web/modal_login.tpl',
            'checkout' => 'component/modal/web/modal_login/modal_login_checkout.tpl',
        ],
    ];

    public function validate(array &$data): void {
        $authController = new AuthController();
        $authController->initContent();

        $data['id'] = 'modal_login';
        $data['triggers_close'] = ['click_close_button'];

        $this->applyDefaultTriggers($data);
    }

    public function getDemoData(): array {
        return [
            'width' => 'small',
            'height' => 'auto',
            'back' => ''
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



