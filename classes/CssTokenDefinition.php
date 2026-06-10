<?php

abstract class CssTokenDefinition {
    protected const TYPE = '';
    protected const NAME = '';
    protected const DEFAULT_STYLE = 'default';
    protected const STYLES = ['default'];
    protected const CSS_CLASSES_BY_STYLE = [];

    public function getType(): string {
        return static::TYPE;
    }

    public function getName(): string {
        return static::NAME;
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

    public function getCssClasses(string $style = ''): string {
        $resolvedStyle = $this->normalizeStyle($style);

        if (empty(static::CSS_CLASSES_BY_STYLE)) {
            throw new PrestaShopException("CSS token {$this->getName()} must define CSS_CLASSES_BY_STYLE.");
        }

        if (!isset(static::CSS_CLASSES_BY_STYLE[static::DEFAULT_STYLE])) {
            throw new PrestaShopException("CSS token {$this->getName()} must define CSS_CLASSES_BY_STYLE['default'].");
        }

        $classes = static::CSS_CLASSES_BY_STYLE[$resolvedStyle] ?? static::CSS_CLASSES_BY_STYLE[static::DEFAULT_STYLE];
        $customClasses = $this->resolveCustomCssClasses($resolvedStyle);

        if ($customClasses !== null && $customClasses !== '') {
            return $customClasses;
        }

        return $classes;
    }

    public static function fetchCssClasses($style = ''): string {
        if (!is_string($style)) {
            $style = '';
        }

        $instance = new static();

        return $instance->getCssClasses($style);
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

    private function resolveCustomCssClasses(string $style): ?string {
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

            $classes = (string)$customCssSelector['custom'];
            $selectorStyle = trim((string)$customCssSelector['style']);

            if ($selectorStyle !== '' && $selectorStyle === $style) {
                return $classes;
            }

            if ($selectorStyle === '' || $selectorStyle === static::DEFAULT_STYLE) {
                $defaultOverride = $classes;
            }
        }

        return $defaultOverride;
    }
}
