<?php

require_once(dirname(__FILE__).'/CssTokenDefinition.php');

class CssTokenRegistry {
    private static ?array $tokens = null;
    private static ?array $cssSelectors = null;

    private static function build(): array {
        $tokens = [];
        $tokensPath = __DIR__ . '/css_tokens';

        if (!is_dir($tokensPath)) {
            return $tokens;
        }

        $directoryIterator = new RecursiveDirectoryIterator($tokensPath, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
                continue;
            }

            $file = $fileInfo->getPathname();
            $before = get_declared_classes();
            require_once $file;
            $after = get_declared_classes();
            $candidateClasses = array_unique(array_merge(array_diff($after, $before), [$fileInfo->getBasename('.php')]));

            foreach ($candidateClasses as $className) {
                if (!class_exists($className, false)) {
                    continue;
                }

                if (!is_subclass_of($className, CssTokenDefinition::class)) {
                    continue;
                }

                $reflection = new ReflectionClass($className);

                if ($reflection->isAbstract()) {
                    continue;
                }

                $tokens[] = new $className();
            }
        }

        return $tokens;
    }

    public static function all(): array {
        if (self::$tokens === null) {
            self::$tokens = self::build();
        }

        return self::$tokens;
    }

    public static function getByName(string $name): ?CssTokenDefinition {
        foreach (self::all() as $token) {
            if ($token->getName() === $name) {
                return $token;
            }
        }

        return null;
    }

    public static function getByType(string $type): array {
        $tokens = [];

        foreach (self::all() as $token) {
            if ($token->getType() === $type) {
                $tokens[] = $token;
            }
        }

        return $tokens;
    }

    public static function getAllCssSelectors(): array {
        if (self::$cssSelectors !== null) {
            return self::$cssSelectors;
        }

        $smartyCssSelectors = [];

        foreach (self::all() as $token) {
            foreach ($token->getStyles() as $style) {
                $cssClasses = $token->getCssClasses($style);
                if ($cssClasses === '') {
                    continue;
                }

                $smartyCssSelectors[$token->getName()][$style] = $cssClasses;
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
