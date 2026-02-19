<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class FlexboxComponentsComponent extends ComponentDefinition {
    protected const TYPE = 'flexbox';
    protected const NAME = 'flexbox_components';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
    }

    public function getDemoData(): array {
        $list_compact_1 = ListCompactComponent::fetchDemo();
        $list_compact_2 = ListCompactComponent::fetchDemo();
        $list_compact_3 = ListCompactComponent::fetchDemo();

        return [
            'nbr_columns' => 3,
            'elements' => [$list_compact_1, $list_compact_2, $list_compact_3]
        ];
    }
}



