<?php

require_once(dirname(__FILE__).'/enums/ComponentChannel.php');
require_once(dirname(__FILE__).'/FrameworkRegistry.php');
require_once(dirname(__DIR__).'/controllers/front/FrameworkController.php');

abstract class ComponentDefinition {
    protected const TYPE = '';
    protected const NAME = '';
    protected const CHANNELS = [];
    protected const SUPPORTS_CACHING = false;
    protected const DEFAULT_STYLE = 'default';
    protected const STYLES = ['default'];
    protected const CSS_CLASSES_BY_STYLE = [];
    protected const TEMPLATE_PATHS_BY_STYLE = [];

    abstract public function getDemoData(): array;

    public function getName(): string {
        return static::NAME;
    }

    private static function fetch(ComponentChannel $channel, array $data = [], string $style = '', bool $ajax = false)
    {
        $instance = new static();
        $resolvedStyle = $instance->normalizeStyle($style);

        if (!in_array($channel, $instance->getChannels(), true)) {
            throw new PrestaShopException("Channel {$channel->value} not supported for component {$instance->getName()}.");
        }

        if ($channel === ComponentChannel::CSS_CLASSES) {
            return $instance->getCssSelector($resolvedStyle);
        }

        $context = Context::getContext();

        $instance->validate($data);
        self::setUniqueId($data, $instance->getName());

        $data['component_name'] = $instance->getName();
        $data['style'] = $resolvedStyle;

        $context->smarty->assign([
            'component' => $data,
            'first_call' => !isset(FrameworkController::$alreadyCalledComponent[$instance->getName().$channel->value.$resolvedStyle]),
            'first_call_type' => !isset(FrameworkController::$alreadyCalledType[$instance->getType().$channel->value]),
        ]);

        FrameworkRegistry::assignCssSelectorsToSmarty();

        FrameworkController::$alreadyCalledComponent[$instance->getName().$channel->value.$resolvedStyle] = true;
        FrameworkController::$alreadyCalledType[$instance->getType().$channel->value] = true;

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
        return static::fetch(ComponentChannel::WEB, $data, $style, $ajax);
    }

    public static function fetchEmail(array $data = [], string $style = '', bool $ajax = false)
    {
        return static::fetch(ComponentChannel::EMAIL, $data, $style, $ajax);
    }

    public static function fetchCssClasses($style = '')
    {
        if (!is_string($style)) {
            $style = '';
        }

        return static::fetch(ComponentChannel::CSS_CLASSES, [], $style, false);
    }

    public static function fetchDemo(ComponentChannel $channel = ComponentChannel::WEB, string $style = '')
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

    public function getCssSelector(string $style = ''): ?string {
        $resolvedStyle = $this->normalizeStyle($style);

        $selector = $this->getCssSelectorByStyle($resolvedStyle);
        if ($selector === null) {
            return null;
        }

        $customSelector = $this->resolveCustomCssSelector($resolvedStyle);
        if ($customSelector !== null && $customSelector !== '') {
            return $customSelector;
        }

        return $selector;
    }

    public function getTemplatePath(ComponentChannel $channel, string $style = ''): string {
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

    private function getCssSelectorByStyle(string $style): ?string {
        $supportsCssChannel = in_array(ComponentChannel::CSS_CLASSES, static::CHANNELS, true);

        if ($supportsCssChannel && empty(static::CSS_CLASSES_BY_STYLE)) {
            throw new PrestaShopException("Component {$this->getName()} must define CSS_CLASSES_BY_STYLE when CSS_CLASSES channel is enabled.");
        }

        if ($supportsCssChannel && !isset(static::CSS_CLASSES_BY_STYLE[static::DEFAULT_STYLE])) {
            throw new PrestaShopException("Component {$this->getName()} must define CSS_CLASSES_BY_STYLE['default'] when CSS_CLASSES channel is enabled.");
        }

        if (isset(static::CSS_CLASSES_BY_STYLE[$style])) {
            return static::CSS_CLASSES_BY_STYLE[$style];
        }

        if (isset(static::CSS_CLASSES_BY_STYLE[static::DEFAULT_STYLE])) {
            return static::CSS_CLASSES_BY_STYLE[static::DEFAULT_STYLE];
        }

        return null;
    }

    private function resolveCustomCssSelector(string $style): ?string {
        $configPath = _PS_THEME_DIR_.'config.xml';
        if (!file_exists($configPath)) {
            return null;
        }

        $configObject = simplexml_load_file($configPath);
        if (!$configObject || !isset($configObject->custom_css_selectors)) {
            return null;
        }

        $defaultOverride = null;

        foreach ($configObject->custom_css_selectors->custom_css_selector as $customCssSelector) {
            if ((string)$customCssSelector['name'] !== static::NAME || !$customCssSelector['custom']) {
                continue;
            }

            $selector = (string)$customCssSelector['custom'];
            $selectorStyle = trim((string)$customCssSelector['style']);

            if ($selectorStyle !== '' && $selectorStyle === $style) {
                return $selector;
            }

            if ($selectorStyle === '' || $selectorStyle === static::DEFAULT_STYLE) {
                $defaultOverride = $selector;
            }
        }

        return $defaultOverride;
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
        if (!isset($data['id']) || !$data['id']) {
            do {
                $unique_id = $componentName.'_'.time().'_'.rand(10000,99999);
            } while (in_array($unique_id, FrameworkController::$ids_unique, true));

            $data['id'] = $unique_id;
        }

        FrameworkController::$ids_unique[] = $data['id'];
    }
}
