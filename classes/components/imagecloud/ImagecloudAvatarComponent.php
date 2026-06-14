<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__, 2).'/mappers/ImagecloudAvatarComponentDataMapper.php');

class ImagecloudAvatarComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_avatar';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;

    public function validate(array &$data): void {
        if (!isset($data['images']) || !is_array($data['images'])) {
            $data['images'] = [];
        }
    }

    public function getDemoData(): array {
        return [
            'images' => ImagecloudAvatarComponentDataMapper::map($this->getTeamCustomerDemoProfileRows(3)),
        ];
    }
}



