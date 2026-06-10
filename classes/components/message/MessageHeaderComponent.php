<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(dirname(__DIR__).'/imagecloud/ImagecloudAvatarComponent.php');

class MessageHeaderComponent extends ComponentDefinition
{
    protected const TYPE = 'message';
    protected const NAME = 'message_header';
    protected const CHANNELS = [\CoreExtension\OutputChannelEnum::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const STYLES = ['default', 'compact'];
    protected const TEMPLATE_PATHS_BY_STYLE = [
        'web' => [
            'compact' => 'component/message/web/message_header_compact.tpl',
        ],
    ];

    public function validate(array &$data): void
    {
        if (!isset($data['avatar'])) {
            $data['avatar'] = '';
        }

        if (!isset($data['avatar_alt'])) {
            $data['avatar_alt'] = '';
        }

        if (!isset($data['title'])) {
            $data['title'] = '';
        }

        if (!isset($data['title_url'])) {
            $data['title_url'] = '';
        }

        if (!isset($data['subtitle'])) {
            $data['subtitle'] = '';
        }

        if (!isset($data['subtitle_icon_html'])) {
            $data['subtitle_icon_html'] = '';
        }

        if (!isset($data['subtitle_activity_text'])) {
            $data['subtitle_activity_text'] = '';
        }

        if (!isset($data['subtitle_elapsed_text'])) {
            $data['subtitle_elapsed_text'] = '';
        }

        if (!isset($data['activity'])) {
            $data['activity'] = '';
        }

        if (!isset($data['player_badge'])) {
            $data['player_badge'] = '';
        }
        $data['player_badge'] = in_array((string)$data['player_badge'], ['employee', 'community'], true)
            ? (string)$data['player_badge']
            : '';

        if (empty($data['menu_items']) || !is_array($data['menu_items'])) {
            $data['menu_items'] = [];
        }
    }

    public function getDemoData(): array
    {
        $profile = ImagecloudAvatarComponent::getKronaAvatarProfiles(1)[0] ?? [];

        return [
            'avatar' => (string)($profile['avatar'] ?? ''),
            'avatar_alt' => (string)($profile['name'] ?? 'Avatar'),
            'style' => 'default',
            'title' => (string)($profile['name'] ?? 'Demo User'),
            'title_url' => (string)($profile['url'] ?? '#'),
            'activity' => 'Opened a discussion',
            'subtitle' => '2 hours ago',
            'player_badge' => 'employee',
            'menu_items' => [
                [
                    'label' => 'Melden',
                    'href' => '#',
                ],
            ],
        ];
    }
}
