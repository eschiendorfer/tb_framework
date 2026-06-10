<?php

require_once(dirname(__FILE__).'/ComponentDefinition.php');

class FrameworkRegistry {
    private static ?array $components = null;

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
            $candidateClasses = array_unique(array_merge(array_diff($after, $before), [$fileInfo->getBasename('.php')]));

            foreach ($candidateClasses as $className) {
                if (!class_exists($className, false)) {
                    continue;
                }

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

    public static function getByChannel(\CoreExtension\OutputChannelEnum $channel): array {
        $components = [];

        foreach (self::all() as $component) {
            if (in_array($channel, $component->getChannels(), true)) {
                $components[] = $component;
            }
        }

        return $components;
    }

}
