<?php

final class FrameworkEntityCollectionLoader
{
    /**
     * @param \CoreExtension\EntityTypeEnum[] $allowedTargetEntityTypes
     * @return \CoreExtension\EntityReference[]
     */
    public static function loadReferences(
        array $params,
        string $channel,
        array $runtimeContext = [],
        array $allowedTargetEntityTypes = []
    ): array {
        if (!class_exists(\CoreExtension\EntityReferenceParser::class)) {
            return [];
        }

        $itemReferences = \CoreExtension\EntityReferenceParser::parseItems($params['items'] ?? '');
        $source = self::normalizeSource((string)($params['source'] ?? ''));
        $hasSource = $source !== '';
        $limit = self::resolveLimit($params, count($itemReferences), $hasSource);
        if ($limit <= 0) {
            return [];
        }

        $references = [];
        $seen = [];
        foreach ($itemReferences as $reference) {
            self::appendReference($references, $seen, $reference, $limit, $allowedTargetEntityTypes);
            if (count($references) >= $limit) {
                return $references;
            }
        }

        if (!$hasSource || !class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)) {
            return $references;
        }

        $sourceParams = $params;
        $sourceParams['limit'] = $limit;
        $sourceParams['runtime_context'] = $runtimeContext;
        $sourceParams['channel'] = $channel;

        foreach (\CoreExtension\EntityCollectionRelationRegistry::loadEntityReferences($source, $sourceParams) as $reference) {
            self::appendReference($references, $seen, $reference, $limit, $allowedTargetEntityTypes);
            if (count($references) >= $limit) {
                break;
            }
        }

        return $references;
    }

    /**
     * @param \CoreExtension\EntityTypeEnum[] $allowedTargetEntityTypes
     */
    public static function loadDataRows(
        array $params,
        string $channel,
        array $runtimeContext = [],
        array $allowedTargetEntityTypes = [],
        ?\CoreExtension\EntityDataProfileEnum $profile = null
    ): array {
        if (!class_exists(\CoreExtension\EntityDataRegistry::class)) {
            return [];
        }

        $channelEnum = \CoreExtension\OutputChannelEnum::tryFrom(strtolower(trim($channel)))
            ?? \CoreExtension\OutputChannelEnum::WEB;
        $profile ??= \CoreExtension\EntityDataProfileEnum::FULL;
        $manualReferences = class_exists(\CoreExtension\EntityReferenceParser::class)
            ? \CoreExtension\EntityReferenceParser::parseItems($params['items'] ?? '')
            : [];
        $references = self::loadReferences($params, $channel, $runtimeContext, $allowedTargetEntityTypes);
        if (empty($references)) {
            return [];
        }

        $dataContext = [
            'channel' => $channel,
            'shortcode_params' => $params,
            'runtime_context' => $runtimeContext,
            'include_inactive_entity_keys' => self::resolveIncludeInactiveEntityKeys($params, $manualReferences),
        ];
        $rowsByKey = \CoreExtension\EntityDataRegistry::getDataRows($references, $channelEnum, $profile, $dataContext);

        $rows = [];
        foreach ($references as $reference) {
            $row = $rowsByKey[$reference->getKey()] ?? null;
            if (!is_array($row) || trim((string)($row['title'] ?? '')) === '') {
                continue;
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public static function getCollectionSourceOptions(?\CoreExtension\EntityTypeEnum $targetEntityType = null): array
    {
        return class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)
            ? \CoreExtension\EntityCollectionRelationRegistry::getOptions($targetEntityType)
            : [];
    }

    public static function getCollectionSourceKeys(?\CoreExtension\EntityTypeEnum $targetEntityType = null): array
    {
        return class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)
            ? \CoreExtension\EntityCollectionRelationRegistry::getKeys($targetEntityType)
            : [];
    }

    public static function getCollectionSourceKeysRequiringSourceId(?\CoreExtension\EntityTypeEnum $targetEntityType = null): array
    {
        return class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)
            ? \CoreExtension\EntityCollectionRelationRegistry::getSourceKeysRequiringSourceId($targetEntityType)
            : [];
    }

    public static function getCollectionSourceKeysSupportingSort(?\CoreExtension\EntityTypeEnum $targetEntityType = null): array
    {
        return class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)
            ? \CoreExtension\EntityCollectionRelationRegistry::getKeysSupportingSort($targetEntityType)
            : [];
    }

    public static function getCollectionSortOptions(?\CoreExtension\EntityTypeEnum $targetEntityType = null): array
    {
        if (!class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)) {
            return [];
        }

        $options = [
            [
                'label' => 'Standard',
                'value' => '',
            ],
        ];
        $seen = ['' => true];

        foreach (\CoreExtension\EntityCollectionRelationRegistry::getOptions($targetEntityType) as $sourceOption) {
            foreach ((array)($sourceOption['sort_options'] ?? []) as $sortOption) {
                $value = trim((string)($sortOption['value'] ?? ''));
                $label = trim((string)($sortOption['label'] ?? ''));
                if ($value === '' || $label === '' || isset($seen[$value])) {
                    continue;
                }

                $seen[$value] = true;
                $options[] = [
                    'label' => $label,
                    'value' => $value,
                ];
            }
        }

        return $options;
    }

    public static function getCollectionTargetEntityTypes(): array
    {
        return class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)
            ? \CoreExtension\EntityCollectionRelationRegistry::getTargetEntityTypes()
            : [];
    }

    /**
     * @param \CoreExtension\EntityReference[] $manualReferences
     * @return string[]
     */
    private static function resolveIncludeInactiveEntityKeys(array $params, array $manualReferences): array
    {
        if (!self::isTruthy($params['include_inactive'] ?? false)) {
            return [];
        }

        $keys = [];
        foreach ($manualReferences as $reference) {
            if (
                $reference instanceof \CoreExtension\EntityReference
                && $reference->getEntityType() === \CoreExtension\EntityTypeEnum::PRODUCT
            ) {
                $keys[] = $reference->getKey();
            }
        }

        return array_values(array_unique($keys));
    }

    private static function isTruthy($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $value = strtolower(trim((string)$value));
        return in_array($value, ['1', 'true', 'yes', 'on'], true);
    }

    /**
     * @param \CoreExtension\EntityTypeEnum[] $allowedTargetEntityTypes
     */
    private static function appendReference(
        array &$references,
        array &$seen,
        \CoreExtension\EntityReference $reference,
        int $limit,
        array $allowedTargetEntityTypes
    ): void {
        if (count($references) >= $limit || isset($seen[$reference->getKey()])) {
            return;
        }

        if (!empty($allowedTargetEntityTypes) && !in_array($reference->getEntityType(), $allowedTargetEntityTypes, true)) {
            return;
        }

        $seen[$reference->getKey()] = true;
        $references[] = $reference;
    }

    private static function resolveLimit(array $params, int $itemCount, bool $hasSource): int
    {
        if (array_key_exists('limit', $params) && trim((string)$params['limit']) !== '') {
            return max(0, (int)$params['limit']);
        }

        return $hasSource ? 5 : max(0, $itemCount);
    }

    private static function normalizeSource(string $source): string
    {
        $source = strtolower(trim($source));
        $source = preg_replace('/[^a-z0-9_]+/', '_', $source) ?: '';

        return trim($source, '_');
    }
}
