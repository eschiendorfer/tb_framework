<?php

final class ImagecloudComponentDataMapper
{
    public static function map(array $entityRows): array
    {
        $items = [];
        foreach ($entityRows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $title = trim((string)($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $items[] = [
                'title' => $title,
                'link' => [
                    'url' => self::resolveUrl($row),
                ],
                'image' => [
                    'src' => trim((string)($row['img'] ?? $row['image_url'] ?? $row['image']['src'] ?? '')),
                ],
            ];
        }

        return $items;
    }

    private static function resolveUrl(array $row): string
    {
        if (isset($row['link']) && is_array($row['link'])) {
            return trim((string)($row['link']['url'] ?? ''));
        }

        if (isset($row['link']) && is_string($row['link'])) {
            return trim($row['link']);
        }

        return trim((string)($row['url'] ?? $row['product_url'] ?? ''));
    }
}
