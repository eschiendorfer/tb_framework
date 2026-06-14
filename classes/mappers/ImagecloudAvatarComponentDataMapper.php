<?php

final class ImagecloudAvatarComponentDataMapper
{
    public static function map(array $entityRows): array
    {
        $images = [];
        foreach ($entityRows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $image = is_array($row['image'] ?? null) ? $row['image'] : [];
            $src = trim((string)($row['avatar'] ?? $row['img'] ?? $row['image_url'] ?? $image['src'] ?? ''));
            if ($src === '') {
                continue;
            }

            $title = trim((string)($row['title'] ?? ''));
            $item = [
                'image' => [
                    'src' => $src,
                    'alt' => $title,
                ],
            ];

            $url = self::resolveUrl($row);
            if ($url !== '') {
                $item['link'] = [
                    'href' => $url,
                    'title' => $title,
                ];
            }

            $images[] = $item;
        }

        return $images;
    }

    private static function resolveUrl(array $row): string
    {
        if (isset($row['link']) && is_array($row['link'])) {
            return trim((string)($row['link']['href'] ?? $row['link']['url'] ?? ''));
        }

        if (isset($row['link']) && is_string($row['link'])) {
            return trim($row['link']);
        }

        return trim((string)($row['url'] ?? ''));
    }
}
