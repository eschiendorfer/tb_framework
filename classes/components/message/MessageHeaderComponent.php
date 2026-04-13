<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class MessageHeaderComponent extends ComponentDefinition
{
    protected const TYPE = 'message';
    protected const NAME = 'message_header';
    protected const CHANNELS = [ComponentChannel::WEB];
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
        return [
            'avatar' => '/upload/genzo_krona/img/avatar/no-avatar.jpg',
            'avatar_alt' => 'Avatar',
            'style' => 'default',
            'title' => 'Demo User',
            'title_url' => '#',
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
