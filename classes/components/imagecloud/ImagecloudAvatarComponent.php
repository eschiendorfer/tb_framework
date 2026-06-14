<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class ImagecloudAvatarComponent extends ComponentDefinition {
    protected const TYPE = 'imagecloud';
    protected const NAME = 'imagecloud_avatar';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;
    private const DEFAULT_AVATAR = '/upload/genzo_krona/img/avatar/no-avatar.jpg';

    public function validate(array &$data): void {
        if (!isset($data['images']) || !is_array($data['images'])) {
            $data['images'] = [];
        }
    }

    public function getDemoData(): array {
        $images = [];

        foreach (self::getDemoProfiles(3) as $profile) {
            $item = [
                'image' => [
                    'imageEntity' => '',
                    'idEntity' => 0,
                    'src' => $profile['avatar'],
                    'width' => 0,
                    'height' => 0,
                    'alt' => $profile['name'],
                ],
                'id' => '',
            ];

            if (trim((string)$profile['url']) !== '') {
                $item['link'] = [
                    'href' => $profile['url'],
                    'title' => $profile['name'],
                ];
            }

            $images[] = $item;
        }

        return [
            'images' => $images,
        ];
    }

    public static function getDemoProfiles(int $limit = 3): array
    {
        if ($limit <= 0) {
            return [];
        }

        return array_slice([
            ['id_customer' => 0, 'name' => 'Demo User', 'avatar' => self::DEFAULT_AVATAR, 'url' => ''],
            ['id_customer' => 0, 'name' => 'Community Member', 'avatar' => self::DEFAULT_AVATAR, 'url' => ''],
            ['id_customer' => 0, 'name' => 'Player Profile', 'avatar' => self::DEFAULT_AVATAR, 'url' => ''],
        ], 0, $limit);
    }
}



