<?php

final class FrameworkRenderState
{
    private static array $alreadyCalledTypes = [];
    private static array $alreadyCalledComponents = [];
    private static array $idsUnique = [];

    public static function isFirstTypeCall(string $type, \CoreExtension\OutputChannelEnum $channel): bool
    {
        return !isset(self::$alreadyCalledTypes[self::typeKey($type, $channel)]);
    }

    public static function markTypeCalled(string $type, \CoreExtension\OutputChannelEnum $channel): void
    {
        self::$alreadyCalledTypes[self::typeKey($type, $channel)] = true;
    }

    public static function isFirstComponentCall(string $componentName, \CoreExtension\OutputChannelEnum $channel, string $style): bool
    {
        return !isset(self::$alreadyCalledComponents[self::componentKey($componentName, $channel, $style)]);
    }

    public static function markComponentCalled(string $componentName, \CoreExtension\OutputChannelEnum $channel, string $style): void
    {
        self::$alreadyCalledComponents[self::componentKey($componentName, $channel, $style)] = true;
    }

    public static function ensureUniqueId(array &$data, string $componentName): void
    {
        if (!isset($data['id']) || !$data['id']) {
            do {
                $uniqueId = $componentName.'_'.time().'_'.rand(10000, 99999);
            } while (in_array($uniqueId, self::$idsUnique, true));

            $data['id'] = $uniqueId;
        }

        self::$idsUnique[] = (string)$data['id'];
    }

    public static function snapshotCallTracking(): array
    {
        return [
            'types' => self::$alreadyCalledTypes,
            'components' => self::$alreadyCalledComponents,
        ];
    }

    public static function resetCallTracking(): void
    {
        self::$alreadyCalledTypes = [];
        self::$alreadyCalledComponents = [];
    }

    public static function restoreCallTracking(array $snapshot): void
    {
        self::$alreadyCalledTypes = is_array($snapshot['types'] ?? null) ? $snapshot['types'] : [];
        self::$alreadyCalledComponents = is_array($snapshot['components'] ?? null) ? $snapshot['components'] : [];
    }

    private static function typeKey(string $type, \CoreExtension\OutputChannelEnum $channel): string
    {
        return $type.$channel->value;
    }

    private static function componentKey(string $componentName, \CoreExtension\OutputChannelEnum $channel, string $style): string
    {
        return $componentName.$channel->value.$style;
    }
}
