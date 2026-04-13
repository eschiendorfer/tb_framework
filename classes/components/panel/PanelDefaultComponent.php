<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');
require_once(__DIR__.'/PanelConfig.php');
require_once(dirname(__DIR__, 2).'/enums/PanelRenderModeEnum.php');

class PanelDefaultComponent extends ComponentDefinition
{
    protected const TYPE = 'panel';
    protected const NAME = 'panel_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;
    protected const ASSET_FILES_BY_STYLE = [
        'default' => [
            'css' => ['views/css/components/panel_default.css'],
            'js' => ['views/js/components/panel_default.js'],
        ],
    ];

    public function validate(array &$data): void
    {
        if (!isset($data['config']) || !($data['config'] instanceof PanelConfig)) {
            throw new PrestaShopException('PanelDefaultComponent requires "config" as PanelConfig.');
        }

        $config = $data['config'];
        $data['config'] = $config->toArray();

        if (!isset($data['content'])) {
            $data['content'] = '';
        }

        if (!is_string($data['content'])) {
            throw new PrestaShopException('PanelDefaultComponent requires "content" as string.');
        }

        if (!isset($data['title'])) {
            $data['title'] = '';
        }

        if (!isset($data['item'])) {
            $data['item'] = '';
        }

        if (!is_string($data['title']) || !is_string($data['item'])) {
            throw new PrestaShopException('PanelDefaultComponent expects "title" and "item" as strings.');
        }

        if (
            $data['config']['render_mode'] === PanelRenderModeEnum::POPOVER->value
            && $data['item'] === ''
            && empty($data['config']['anchor_element_id'])
        ) {
            throw new PrestaShopException('PanelDefaultComponent in popover mode requires "item" or "config.anchor_element_id".');
        }
    }

    public function getDemoData(): array
    {
        return [
            'title' => 'Panel',
            'content' => '<p class="text-sm text-gray-700">Panel-Inhalt</p>',
            'item' => '<button type="button" class="px-3 py-2 text-sm border rounded">Panel oeffnen</button>',
            'config' => PanelConfig::dialogSheet(),
        ];
    }
}
