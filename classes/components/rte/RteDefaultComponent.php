<?php

require_once(dirname(__DIR__, 2).'/ComponentDefinition.php');

class RteDefaultComponent extends ComponentDefinition {
    protected const TYPE = 'rte';
    protected const NAME = 'rte_default';
    protected const CHANNELS = [ComponentChannel::WEB];
    protected const SUPPORTS_CACHING = false;
    private const DEFAULT_FEATURES = ['bold', 'bullet', 'emoji', 'mention', 'youtube', 'link', 'image', 'attachment'];
    private const ALLOWED_FEATURES = ['bold', 'bullet', 'emoji', 'mention', 'youtube', 'link', 'image', 'attachment', 'poll'];

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

        if (!isset($data['media_value'])) {
            $data['media_value'] = '';
        }

        if (!isset($data['tiptap'])) {
            $data['tiptap'] = true;
        }

        if (!isset($data['upload_url'])) {
            $data['upload_url'] = '';
        }

        if (!isset($data['upload_context'])) {
            $data['upload_context'] = '';
        }

        if (!isset($data['hero_enabled'])) {
            $data['hero_enabled'] = true;
        }
        $data['hero_enabled'] = (bool)$data['hero_enabled'];

        $mode = strtolower(trim((string)($data['mode'] ?? 'default')));
        if (!in_array($mode, ['default', 'compact'], true)) {
            $mode = 'default';
        }
        $data['mode'] = $mode;

        if (!isset($data['placeholder'])) {
            $data['placeholder'] = '';
        }
        $data['placeholder'] = trim((string)$data['placeholder']);

        $data['features'] = $this->normalizeFeatures($data['features'] ?? null);
        $data['features_csv'] = implode(',', $data['features']);
    }

    public function getDemoData(): array {
        return [
            'name' => 'rte_demo',
            'value' => 'Demo Text',
            'cta' => '',
            'autoload' => true,
            'tiptap' => true,
            'media_value' => '',
            'upload_url' => '',
            'upload_context' => '',
            'hero_enabled' => true,
            'mode' => 'default',
            'placeholder' => '',
            'features' => self::DEFAULT_FEATURES,
            'features_csv' => implode(',', self::DEFAULT_FEATURES),
        ];
    }

    private function normalizeFeatures($features): array
    {
        if (empty($features)) {
            return self::DEFAULT_FEATURES;
        }

        if (is_string($features)) {
            $features = explode(',', $features);
        }

        if (!is_array($features)) {
            return self::DEFAULT_FEATURES;
        }

        $normalized = [];
        foreach ($features as $feature) {
            if (!is_string($feature)) {
                continue;
            }

            $feature = trim(strtolower($feature));
            if ($feature === '') {
                continue;
            }

            if (!in_array($feature, self::ALLOWED_FEATURES, true)) {
                continue;
            }

            if (!in_array($feature, $normalized, true)) {
                $normalized[] = $feature;
            }
        }

        return empty($normalized) ? self::DEFAULT_FEATURES : $normalized;
    }
}



