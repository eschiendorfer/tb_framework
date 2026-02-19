<?php

if (!class_exists('TbFrameworkAutoloader', false)) {
    final class TbFrameworkAutoloader {
        private static ?array $classMap = null;

        public static function register(): void {
            $callback = [self::class, 'loadClass'];
            $autoloadFunctions = spl_autoload_functions() ?: [];

            foreach ($autoloadFunctions as $autoloadFunction) {
                if ($autoloadFunction === $callback) {
                    return;
                }
            }

            spl_autoload_register($callback);
        }

        public static function loadClass(string $className): void {
            $map = self::getClassMap();

            if (isset($map[$className])) {
                require_once $map[$className];
            }
        }

        private static function getClassMap(): array {
            if (self::$classMap !== null) {
                return self::$classMap;
            }

            $classMap = [];
            $classesPath = __DIR__ . '/classes';

            if (is_dir($classesPath)) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($classesPath, RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $fileInfo) {
                    if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
                        continue;
                    }

                    $classMap[$fileInfo->getBasename('.php')] = $fileInfo->getPathname();
                }
            }

            self::$classMap = $classMap;
            return self::$classMap;
        }
    }
}

TbFrameworkAutoloader::register();
