<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(__DIR__.'/RteDefaultComponent.php');

class RteCompactComponent extends RteDefaultComponent {
    protected const NAME = 'rte_compact';
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'default' => 'component/rte/web/rte_default.tpl',
        ],
    ];

    public function validate(array &$data): void
    {
        $data['mode'] = 'compact';
        parent::validate($data);
        $data['mode'] = 'compact';
    }

    public function getDemoData(): array
    {
        $data = parent::getDemoData();
        $data['mode'] = 'compact';
        return $data;
    }
}
