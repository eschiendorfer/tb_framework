<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class RteDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'rte';
    protected const NAME = 'rte_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (!isset($data['autoload'])) {
            $data['autoload'] = true;
        }

        if (empty($data['name'])) {
            $data['name'] = 'rte';
        }

        if (!isset($data['cta'])) {
            $data['cta'] = '';
        }

        if (!isset($data['value'])) {
            $data['value'] = '';
        }
    }

    public function getDemoData(): array {
        return [
            'name' => 'rte_demo',
            'value' => 'Demo Text',
            'cta' => '',
            'autoload' => true,
        ];
    }
}



