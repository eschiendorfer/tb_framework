<?php

final class ComponentJsonDataLoader
{
    private const BASE_DIRECTORY = 'data/json';

    public static function loadFromParams(array $params, ComponentDefinition $component): array
    {
        $json = trim((string)($params['json'] ?? ''));
        if ($json === '') {
            return [];
        }

        return self::load($component, $json);
    }

    public static function load(ComponentDefinition $component, string $json): array
    {
        $filePath = self::resolveFilePath($component->getName(), $json);
        if ($filePath === '') {
            return [];
        }

        $data = json_decode((string)file_get_contents($filePath), true);

        return is_array($data) ? $data : [];
    }

    public static function getJsonFileOptionsForComponentName(string $componentName): array
    {
        $options = [
            [
                'label' => 'Bitte waehlen',
                'value' => '',
            ],
        ];

        foreach (self::getJsonFileNames($componentName) as $fileName) {
            $options[] = [
                'label' => $fileName,
                'value' => $fileName,
            ];
        }

        return $options;
    }

    public static function getJsonFileNames(string $componentName): array
    {
        $directory = self::getComponentDirectory($componentName, false);
        if ($directory === '' || !is_dir($directory)) {
            return [];
        }

        $fileNames = [];
        foreach (new DirectoryIterator($directory) as $fileInfo) {
            if (!$fileInfo->isFile() || strtolower($fileInfo->getExtension()) !== 'json') {
                continue;
            }

            $fileNames[] = $fileInfo->getBasename();
        }

        sort($fileNames);

        return $fileNames;
    }

    public static function saveUploadedFile(ComponentDefinition $component, array $file): string
    {
        if (empty($file) || !isset($file['error']) || (int)$file['error'] !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('Keine gueltige JSON-Datei hochgeladen.');
        }

        $tmpName = (string)($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_file($tmpName)) {
            throw new InvalidArgumentException('Upload-Datei konnte nicht gelesen werden.');
        }

        $content = (string)file_get_contents($tmpName);
        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            throw new InvalidArgumentException('Die Datei enthaelt kein gueltiges JSON-Objekt oder Array.');
        }

        $fileName = self::normalizeUploadedFileName((string)($file['name'] ?? 'component.json'));
        $directory = self::getComponentDirectory($component->getName(), true);
        if ($directory === '') {
            throw new RuntimeException('JSON-Zielordner konnte nicht ermittelt werden.');
        }

        $targetPath = $directory . $fileName;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            if (!copy($tmpName, $targetPath)) {
                throw new RuntimeException('JSON-Datei konnte nicht gespeichert werden.');
            }
        }

        return $fileName;
    }

    public static function getDemoJson(ComponentDefinition $component): string
    {
        $json = json_encode($component->getDemoData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $json === false ? '{}' : $json;
    }

    private static function resolveFilePath(string $componentName, string $json): string
    {
        $fileName = self::normalizeReferencedFileName($json);
        if ($fileName === '') {
            return '';
        }

        $filePath = self::getComponentDirectory($componentName, false) . $fileName;

        return is_file($filePath) ? $filePath : '';
    }

    private static function getComponentDirectory(string $componentName, bool $create): string
    {
        $componentName = strtolower(trim($componentName));
        if ($componentName === '' || !preg_match('/^[a-z0-9_]+$/', $componentName)) {
            return '';
        }

        $directory = _PS_MODULE_DIR_ . 'tb_framework/' . self::BASE_DIRECTORY . '/' . $componentName . '/';
        if ($create && !is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            return '';
        }

        return $directory;
    }

    private static function normalizeReferencedFileName(string $json): string
    {
        $fileName = basename(str_replace('\\', '/', trim($json)));

        return preg_match('/^[a-zA-Z0-9_.-]+\.json$/', $fileName) ? $fileName : '';
    }

    private static function normalizeUploadedFileName(string $fileName): string
    {
        $fileName = strtolower((string)preg_replace('/[^a-zA-Z0-9_.-]+/', '-', basename($fileName)));
        $fileName = trim($fileName, '.-');

        if ($fileName === '' || !str_ends_with($fileName, '.json')) {
            $fileName .= '.json';
        }

        return self::normalizeReferencedFileName($fileName) ?: 'component.json';
    }
}
