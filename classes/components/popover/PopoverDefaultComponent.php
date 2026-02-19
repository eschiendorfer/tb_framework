<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__).'/card/CardProductComponent.php');

class PopoverDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'popover';
    protected const NAME = 'popover_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (empty($data['item'])) {
            die('popover item is empty');
        }

        if (empty($data['popover_content'])) {
            die('popover content is empty');
        }

        if (empty($data['triggers_show']) || !is_array($data['triggers_show'])) {
            $data['triggers_show'] = ['click_item'];
        }

        if (empty($data['triggers_close']) || !is_array($data['triggers_close'])) {
            $data['triggers_close'] = ['click_item', 'click_outside', 'open_other_item'];
        }

        if (empty($data['position'])) {
            $data['position'] = 'bottom_center';
        }

        if (empty($data['zIndex'])) {
            $data['zIndex'] = 'default';
        }

        if (empty($data['margin'])) {
            $data['margin'] = 'default';
        }
    }

    public function getDemoData(): array {
        /* Possible positions:
            'bottom_left', 'bottom_center', 'bottom_right',
            'top_left', 'top_center', 'top_right',
            'left_top', left_center', 'left_bottom',
            'right_top', right_center', 'right_bottom',
        */

        $cardProductComponent = new CardProductComponent();

        return [
            'item' => 'Hover me',
            'popover_content' => CardProductComponent::fetchWeb($cardProductComponent->getDemoData()),
            'triggers_show' => ['click_item'],
            'triggers_close' => ['click_item', 'click_outside', 'open_other_item'],
            'position' => 'bottom_center',
            'zIndex' => 'default',
            'margin' => 10,
        ];
    }
}



