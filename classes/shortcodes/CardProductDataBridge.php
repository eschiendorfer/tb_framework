<?php

require_once(dirname(__DIR__).'/enums/ComponentChannel.php');
require_once(dirname(__DIR__).'/EntityDataLoader.php');

final class CardProductDataBridge
{
    public const ENTITY_TYPE_PRODUCT = EntityDataLoader::ENTITY_TYPE_PRODUCT;

    public static function resolveEntityData(
        string $entityType,
        int $idEntity,
        ComponentChannel $channel
    ): array {
        $entityType = trim(strtolower($entityType));

        if ($entityType !== self::ENTITY_TYPE_PRODUCT || $idEntity <= 0) {
            return [];
        }

        return EntityDataLoader::loadByEntityType($entityType, $idEntity, $channel);
    }
}
