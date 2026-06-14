<?php

if (defined('_PS_MODULE_DIR_') && file_exists(_PS_MODULE_DIR_ . 'extending_core_files/ExtendingCoreFilesAutoloader.php')) {
    require_once _PS_MODULE_DIR_ . 'extending_core_files/ExtendingCoreFilesAutoloader.php';
}

require_once(dirname(__FILE__).'/CssTokenRegistry.php');
require_once(dirname(__FILE__).'/FrameworkRegistry.php');
require_once(dirname(__FILE__).'/FrameworkRenderState.php');

abstract class ComponentDefinition {
    protected const TYPE = '';
    protected const NAME = '';
    protected const CHANNELS = [];
    protected const SUPPORTS_CACHING = false;
    protected const DEFAULT_STYLE = 'default';
    protected const STYLES = ['default'];
    protected const TEMPLATE_PATHS_BY_STYLE = [];
    protected const ASSET_FILES_BY_STYLE = [];
    private static $registeredAssetUris = [];

    abstract public function getDemoData(): array;

    protected function getTeamCustomerDemoProfileRows(int $limit = 3): array
    {
        if (
            $limit <= 0
            || !class_exists('SpielezarHelper')
            || !method_exists('SpielezarHelper', 'getTeamMembers')
            || !class_exists(\CoreExtension\EntityDataRegistry::class)
            || !class_exists(\CoreExtension\EntityReference::class)
            || !enum_exists(\CoreExtension\EntityTypeEnum::class)
            || !enum_exists(\CoreExtension\OutputChannelEnum::class)
            || !enum_exists(\CoreExtension\EntityDataProfileEnum::class)
        ) {
            return [];
        }

        $references = [];
        foreach (\SpielezarHelper::getTeamMembers() as $idCustomer) {
            $references[] = new \CoreExtension\EntityReference(
                \CoreExtension\EntityTypeEnum::KRONA_CUSTOMER_PROFILE,
                (int)$idCustomer
            );
        }

        if (empty($references)) {
            return [];
        }

        try {
            $rowsByKey = \CoreExtension\EntityDataRegistry::getDataRows(
                $references,
                \CoreExtension\OutputChannelEnum::WEB,
                \CoreExtension\EntityDataProfileEnum::SUMMARY
            );
        } catch (\Throwable) {
            return [];
        }

        $rows = [];
        foreach ($references as $reference) {
            $row = $rowsByKey[$reference->getKey()] ?? null;
            if (is_array($row) && trim((string)($row['title'] ?? '')) !== '') {
                $rows[] = $row;
            }
        }

        return array_slice($rows, 0, $limit);
    }

    public static function addTrackingToData(array &$data, array $conversionTypes, int $entityType, int $entityId): void
    {
        if ($entityType <= 0 || $entityId <= 0) {
            return;
        }

        $conversionTypes = array_values(array_unique(array_filter(array_map('intval', $conversionTypes))));
        if (empty($conversionTypes)) {
            return;
        }

        $payload = [
            'conversion_types' => $conversionTypes,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ];

        $data['conversion'] = $payload;
        $data['conversion_json'] = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function getName(): string {
        return static::NAME;
    }

    private static function fetch(\CoreExtension\OutputChannelEnum $channel, array $data = [], string $style = '', bool $ajax = false)
    {
        $instance = new static();
        $resolvedStyle = $instance->normalizeStyle($style);

        if (!in_array($channel, $instance->getChannels(), true)) {
            throw new PrestaShopException("Channel {$channel->value} not supported for component {$instance->getName()}.");
        }

        $context = Context::getContext();

        $instance->validate($data);
        self::setUniqueId($data, $instance->getName());

        $data['component_name'] = $instance->getName();
        $data['style'] = $resolvedStyle;
        $instance->attachAssets($data, $resolvedStyle, $ajax, $context);

        $context->smarty->assign([
            'component' => $data,
            'first_call' => FrameworkRenderState::isFirstComponentCall($instance->getName(), $channel, $resolvedStyle),
            'first_call_type' => FrameworkRenderState::isFirstTypeCall($instance->getType(), $channel),
        ]);

        CssTokenRegistry::assignCssSelectorsToSmarty();

        FrameworkRenderState::markComponentCalled($instance->getName(), $channel, $resolvedStyle);
        FrameworkRenderState::markTypeCalled($instance->getType(), $channel);

        $templatePath = self::resolveTemplatePath($instance->getTemplatePath($channel, $resolvedStyle), $instance->getName());
        $htmlElement = $context->smarty->fetch($templatePath);

        if (!empty($data['container'])) {
            $context->smarty->assign([
                'component' => $htmlElement,
                'boxed' => $data['container']['boxed'] ?? false,
                'margin' => $data['container']['margin'] ?? false,
            ]);

            $htmlElement = $context->smarty->fetch(_PS_THEME_DIR_ . 'component/component_container.tpl');
        }

        if ($ajax) {
            return [
                'name' => $instance->getName(),
                'id' => $data['id'],
                'htmlElement' => $htmlElement,
            ];
        }

        return $htmlElement;
    }

    public static function fetchWeb(array $data = [], string $style = '', bool $ajax = false)
    {
        return static::fetch(\CoreExtension\OutputChannelEnum::WEB, $data, $style, $ajax);
    }

    public static function fetchEmail(array $data = [], string $style = '', bool $ajax = false)
    {
        return static::fetch(\CoreExtension\OutputChannelEnum::EMAIL, $data, $style, $ajax);
    }

    public static function fetchDemo(\CoreExtension\OutputChannelEnum $channel = \CoreExtension\OutputChannelEnum::WEB, string $style = '')
    {
        $instance = new static();
        $data = $instance->getDemoData();

        return static::fetch($channel, $data, $style);
    }

    public function getType(): string {
        return static::TYPE;
    }

    public function getChannels(): array {
        return static::CHANNELS;
    }

    public function supportsCaching(): bool {
        return static::SUPPORTS_CACHING;
    }

    public function getDefaultStyle(): string {
        return static::DEFAULT_STYLE;
    }

    public function getStyles(): array {
        $styles = static::STYLES;

        if (empty($styles)) {
            return [static::DEFAULT_STYLE];
        }

        if (!in_array(static::DEFAULT_STYLE, $styles, true)) {
            array_unshift($styles, static::DEFAULT_STYLE);
        }

        return array_values(array_unique($styles));
    }

    public function getTemplatePath(\CoreExtension\OutputChannelEnum $channel, string $style = ''): string {
        $resolvedStyle = $this->normalizeStyle($style);
        $channelValue = $channel->value;

        if (isset(static::TEMPLATE_PATHS_BY_STYLE[$channelValue][$resolvedStyle])) {
            $mappedPath = static::TEMPLATE_PATHS_BY_STYLE[$channelValue][$resolvedStyle];
            if (self::templateExists($mappedPath)) {
                return $mappedPath;
            }
        }

        if (isset(static::TEMPLATE_PATHS_BY_STYLE[$channelValue][static::DEFAULT_STYLE])) {
            $mappedDefaultPath = static::TEMPLATE_PATHS_BY_STYLE[$channelValue][static::DEFAULT_STYLE];
            if (self::templateExists($mappedDefaultPath)) {
                return $mappedDefaultPath;
            }
        }

        if ($resolvedStyle !== static::DEFAULT_STYLE) {
            $stylePath = 'component/'.static::TYPE.'/'.$channelValue.'/'.static::NAME.'_'.$resolvedStyle.'.tpl';
            if (self::templateExists($stylePath)) {
                return $stylePath;
            }
        }

        return 'component/'.static::TYPE.'/'.$channelValue.'/'.static::NAME.'.tpl';
    }

    public function getAssetFiles(string $style = ''): array
    {
        $resolvedStyle = $this->normalizeStyle($style);
        $assetFiles = static::ASSET_FILES_BY_STYLE[$resolvedStyle] ?? static::ASSET_FILES_BY_STYLE[static::DEFAULT_STYLE] ?? [];

        $cssFiles = $this->normalizeAssetFileList($assetFiles['css'] ?? []);
        $jsFiles = $this->normalizeAssetFileList($assetFiles['js'] ?? []);

        return [
            'css' => $cssFiles,
            'js' => $jsFiles,
        ];
    }

    private function normalizeStyle(string $style): string {
        $style = trim($style);

        if ($style === '') {
            return static::DEFAULT_STYLE;
        }

        if (!in_array($style, $this->getStyles(), true)) {
            return static::DEFAULT_STYLE;
        }

        return $style;
    }

    private function normalizeAssetFileList($assetFiles): array
    {
        if (!is_array($assetFiles)) {
            return [];
        }

        $normalized = [];
        foreach ($assetFiles as $assetFile) {
            if (!is_string($assetFile)) {
                continue;
            }

            $assetFile = trim(str_replace('\\', '/', $assetFile));
            if ($assetFile === '') {
                continue;
            }

            $assetFile = ltrim($assetFile, '/');
            $assetPath = _PS_MODULE_DIR_.'tb_framework/'.$assetFile;
            if (!file_exists($assetPath)) {
                continue;
            }

            if (!in_array($assetFile, $normalized, true)) {
                $normalized[] = $assetFile;
            }
        }

        return $normalized;
    }

    private function attachAssets(array &$data, string $resolvedStyle, bool $ajax, Context $context): void
    {
        $assetFiles = $this->getAssetFiles($resolvedStyle);
        if (empty($assetFiles['css']) && empty($assetFiles['js'])) {
            return;
        }

        $assetUris = [
            'css' => array_map([self::class, 'resolveModuleAssetUri'], $assetFiles['css']),
            'js' => array_map([self::class, 'resolveModuleAssetUri'], $assetFiles['js']),
        ];

        if ($ajax) {
            $data['component_assets'] = $assetUris;
            return;
        }

        if (!isset($context->controller)) {
            return;
        }

        foreach ($assetUris['css'] as $cssUri) {
            if (!$cssUri || isset(self::$registeredAssetUris['css'][$cssUri])) {
                continue;
            }

            if (method_exists($context->controller, 'addCSS')) {
                $context->controller->addCSS($cssUri);
            }
            self::$registeredAssetUris['css'][$cssUri] = true;
        }

        foreach ($assetUris['js'] as $jsUri) {
            if (!$jsUri || isset(self::$registeredAssetUris['js'][$jsUri])) {
                continue;
            }

            if (method_exists($context->controller, 'addJS')) {
                $context->controller->addJS($jsUri);
            }
            self::$registeredAssetUris['js'][$jsUri] = true;
        }
    }

    private static function resolveModuleAssetUri(string $assetFile): string
    {
        $assetFile = ltrim(str_replace('\\', '/', trim($assetFile)), '/');
        return __PS_BASE_URI__.'modules/tb_framework/'.$assetFile;
    }

    private static function templateExists(string $filePathRelative): bool {
        return file_exists(_PS_THEME_DIR_.$filePathRelative)
            || file_exists(_PS_MODULE_DIR_.'tb_framework/'.$filePathRelative);
    }

    private static function resolveTemplatePath(string $filePathRelative, string $name): string {
        if (file_exists(_PS_THEME_DIR_.$filePathRelative)) {
            return _PS_THEME_DIR_.$filePathRelative;
        }

        if (file_exists(_PS_MODULE_DIR_.'tb_framework/'.$filePathRelative)) {
            return _PS_MODULE_DIR_.'tb_framework/'.$filePathRelative;
        }

        throw new PrestaShopException("Tpl for Element {$name} not found!");
    }

    private static function setUniqueId(array &$data, string $componentName): void {
        FrameworkRenderState::ensureUniqueId($data, $componentName);
    }
}
