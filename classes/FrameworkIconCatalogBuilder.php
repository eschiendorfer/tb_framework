<?php

final class FrameworkIconCatalogBuilder {
    private const TYPE = 'icons';
    private ?array $icons = null;

    public function all(): array {
        if ($this->icons !== null) {
            return $this->icons;
        }

        $css = $this->readIconCss();
        if ($css === '') {
            $this->icons = [];

            return $this->icons;
        }

        preg_match_all('/\.((?:icon-[a-z0-9-]+))\s*\{([^}]*)\}/i', $css, $matches, PREG_SET_ORDER);

        $icons = [];
        foreach ($matches as $match) {
            $className = strtolower(trim($match[1]));
            if ($className === 'icon-big' || $className === 'icon-active') {
                continue;
            }

            $body = (string)$match[2];
            $source = $this->extractSource($body);
            $isLarge = str_ends_with($className, '-big') || stripos($body, 'background-image') !== false;
            $name = substr($className, strlen('icon-'));

            $icons[$className] = [
                'type' => self::TYPE,
                'name' => $name,
                'class' => $className,
                'group' => $this->extractGroup($source),
                'source' => $source,
                'is_large' => $isLarge,
                'usage' => $isLarge
                    ? '<i class="icon-big '.$className.'"></i>'
                    : '<i class="icon '.$className.'"></i>',
            ];
        }

        uasort($icons, static function (array $left, array $right): int {
            $groupComparison = strcmp($left['group'], $right['group']);
            if ($groupComparison !== 0) {
                return $groupComparison;
            }

            return strcmp($left['name'], $right['name']);
        });

        $this->icons = array_values($icons);

        return $this->icons;
    }

    public function count(): int {
        return count($this->all());
    }

    public function grouped(): array {
        $groups = [];

        foreach ($this->all() as $icon) {
            $groupKey = $icon['group'];
            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [
                    'key' => $groupKey,
                    'label' => $this->formatGroupLabel($groupKey),
                    'icons' => [],
                ];
            }

            $groups[$groupKey]['icons'][] = $icon;
        }

        return array_values($groups);
    }

    public function getType(): string {
        return self::TYPE;
    }

    private function readIconCss(): string {
        foreach ($this->getIconCssPaths() as $path) {
            if (is_string($path) && is_file($path)) {
                return (string)file_get_contents($path);
            }
        }

        return '';
    }

    private function getIconCssPaths(): array {
        $paths = [];

        if (defined('_PS_THEME_DIR_')) {
            $paths[] = _PS_THEME_DIR_.'css/autoload/icons.css';
        }

        if (defined('_PS_ROOT_DIR_')) {
            $paths[] = _PS_ROOT_DIR_.'/themes/genzo_theme/css/autoload/icons.css';
        }

        return array_values(array_unique($paths));
    }

    private function extractSource(string $cssBody): string {
        if (preg_match('/(?:mask-image|background-image)\s*:\s*url\([\'"]?([^\'")]+)[\'"]?\)/i', $cssBody, $match)) {
            return trim($match[1]);
        }

        return '';
    }

    private function extractGroup(string $source): string {
        if (preg_match('~(?:^|/)img/icons/([^/]+)/~', $source, $match)) {
            return strtolower(trim($match[1]));
        }

        return 'custom';
    }

    private function formatGroupLabel(string $group): string {
        return ucwords(str_replace(['_', '-'], ' ', $group));
    }
}
