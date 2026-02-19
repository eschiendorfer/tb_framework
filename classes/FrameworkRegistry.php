<?php

require_once(dirname(__FILE__).'/enums/ComponentChannel.php');
require_once(dirname(__FILE__).'/ComponentDefinition.php');

class FrameworkRegistry {
    private static ?array $components = null;
    private static ?array $cssSelectors = null;

    private static function build(): array {
        $components = [];
        $componentsPath = __DIR__ . '/components';

        if (!is_dir($componentsPath)) {
            return $components;
        }

        $directoryIterator = new RecursiveDirectoryIterator($componentsPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
                continue;
            }

            $file = $fileInfo->getPathname();
            $before = get_declared_classes();
            require_once $file;
            $after = get_declared_classes();

            foreach (array_diff($after, $before) as $className) {
                if (!is_subclass_of($className, ComponentDefinition::class)) {
                    continue;
                }

                $reflection = new ReflectionClass($className);

                if ($reflection->isAbstract()) {
                    continue;
                }

                $components[] = new $className();
            }
        }

        return $components;
    }

    public static function all(): array {
        if (self::$components === null) {
            self::$components = self::build();
        }

        return self::$components;
    }

    public static function getByName(string $name): ?ComponentDefinition {
        foreach (self::all() as $component) {
            if ($component->getName() === $name) {
                return $component;
            }
        }

        return null;
    }

    public static function getByChannel(ComponentChannel $channel): array {
        $components = [];

        foreach (self::all() as $component) {
            if (in_array($channel, $component->getChannels(), true)) {
                $components[] = $component;
            }
        }

        return $components;
    }

    public static function getAllCssSelectors(): array {
        if (self::$cssSelectors !== null) {
            return self::$cssSelectors;
        }

        $smartyCssSelectors = [];

        foreach (self::all() as $component) {
            if (!in_array(ComponentChannel::CSS_CLASSES, $component->getChannels(), true)) {
                continue;
            }

            if (!method_exists($component, 'getCssSelector')) {
                continue;
            }

            foreach ($component->getStyles() as $style) {
                $styleSelector = $component->getCssSelector($style);
                if (!$styleSelector) {
                    continue;
                }

                $smartyCssSelectors[$component->getName()][$style] = $styleSelector;
            }
        }

        self::$cssSelectors = $smartyCssSelectors;
        return self::$cssSelectors;
    }

    public static function assignCssSelectorsToSmarty(): void {
        $context = Context::getContext();

        if (!isset($context->smarty)) {
            return;
        }

        if (!isset($context->smarty->tpl_vars['css_selector'])) {
            $context->smarty->assign('css_selector', self::getAllCssSelectors());
        }
    }
}
