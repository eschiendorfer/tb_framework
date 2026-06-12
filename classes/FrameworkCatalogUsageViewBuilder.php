<?php

final class FrameworkCatalogUsageViewBuilder
{
    private ?array $shortcodeContractsByName = null;

    public function build(
        array $usageDefinitions,
        array $selectedTargetEntityTypeKeys = [],
        array $selectedDataInputModes = []
    ): array
    {
        $items = [];

        foreach ($usageDefinitions as $usageDefinition) {
            if (!$usageDefinition instanceof \ShortcodeModule\ShortcodeUsageDefinition) {
                continue;
            }

            if (!$this->matchesTargetEntityTypeFilter($usageDefinition, $selectedTargetEntityTypeKeys)) {
                continue;
            }

            if (!$this->matchesDataInputModeFilter($usageDefinition, $selectedDataInputModes)) {
                continue;
            }

            $items[] = [
                'label' => 'Shortcode ' . $usageDefinition->getDataInputMode()->getLabel(),
                'target_entity_types' => $usageDefinition->getTargetEntityTypeKeys(),
                'examples' => $this->buildExamples($usageDefinition),
            ];
        }

        usort($items, static function (array $left, array $right): int {
            return strcmp((string)$left['label'], (string)$right['label']);
        });

        return $this->groupItemsByUsage($items);
    }

    private function matchesTargetEntityTypeFilter(
        \ShortcodeModule\ShortcodeUsageDefinition $usageDefinition,
        array $selectedTargetEntityTypeKeys
    ): bool {
        if (empty($selectedTargetEntityTypeKeys)) {
            return true;
        }

        $targetEntityTypeKeys = $usageDefinition->getTargetEntityTypeKeys();
        if (empty($targetEntityTypeKeys)) {
            return in_array('none', $selectedTargetEntityTypeKeys, true);
        }

        return !empty(array_intersect($targetEntityTypeKeys, $selectedTargetEntityTypeKeys));
    }

    private function matchesDataInputModeFilter(
        \ShortcodeModule\ShortcodeUsageDefinition $usageDefinition,
        array $selectedDataInputModes
    ): bool {
        if (empty($selectedDataInputModes)) {
            return true;
        }

        return in_array($usageDefinition->getDataInputMode(), $selectedDataInputModes, true);
    }

    private function buildExamples(\ShortcodeModule\ShortcodeUsageDefinition $usageDefinition): array
    {
        if ($usageDefinition->getDataInputMode() !== \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION) {
            return [
                [
                    'type' => 'default_' . $usageDefinition->getShortcodeName(),
                    'code' => $this->buildExample($usageDefinition, $this->buildExampleParams($usageDefinition)),
                ],
            ];
        }

        $examples = [];
        $itemsField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_ITEMS
        );
        $sourceField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE
        );
        $sourceIdField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE_ID
        );
        $sortField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SORT
        );

        if ($itemsField !== '') {
            $itemParams = $this->buildBaseCollectionExampleParams($usageDefinition);
            $itemParams[$itemsField] = $this->buildItemsExampleValue($usageDefinition);
            $examples[] = [
                'type' => 'items',
                'code' => $this->buildExample($usageDefinition, $itemParams),
            ];
        }

        $source = $this->selectExampleSource($usageDefinition);
        if ($sourceField !== '' && $source !== '') {
            $sourceParams = $this->buildBaseCollectionExampleParams($usageDefinition);
            $sourceParams[$sourceField] = $source;
            if ($sourceIdField !== '' && $this->sourceRequiresSourceId($source)) {
                $sourceParams[$sourceIdField] = '123';
            }
            if ($sortField !== '' && $this->sourceSupportsSort($source)) {
                $sourceParams[$sortField] = $this->selectExampleSort($source);
            }
            $examples[] = [
                'type' => 'source',
                'code' => $this->buildExample($usageDefinition, $sourceParams),
            ];
        }

        if (empty($examples)) {
            $examples[] = [
                'type' => 'default_' . $usageDefinition->getShortcodeName(),
                'code' => $this->buildExample($usageDefinition, $this->buildExampleParams($usageDefinition)),
            ];
        }

        return $examples;
    }

    private function buildExample(\ShortcodeModule\ShortcodeUsageDefinition $usageDefinition, array $params): string
    {
        $shortcodeName = $usageDefinition->getShortcodeName();
        if ($shortcodeName === '') {
            return '';
        }

        $attributes = [];
        foreach ($params as $param => $value) {
            $attributes[] = $param . '="' . $value . '"';
        }

        $attributeString = empty($attributes) ? '' : ' ' . implode(' ', $attributes);
        if ($this->isContentBlock($shortcodeName)) {
            return '[' . $shortcodeName . $attributeString . ']Inhalt[/' . $shortcodeName . ']';
        }

        return '[' . $shortcodeName . $attributeString . ']';
    }

    private function buildExampleParams(\ShortcodeModule\ShortcodeUsageDefinition $usageDefinition): array
    {
        $availableParams = array_fill_keys($usageDefinition->getParams(), true);
        $params = [];
        $primaryVariantField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::PRIMARY_VARIANT
        );
        $styleField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::PRESENTATION_STYLE
        );

        if ($primaryVariantField !== '') {
            $params[$primaryVariantField] = $usageDefinition->getComponentName();
        }

        if ($styleField !== '') {
            $params[$styleField] = $usageDefinition->getComponentName() === 'box_accordion' ? 'sources' : 'default';
        }

        $dataInputMode = $usageDefinition->getDataInputMode();
        if ($dataInputMode === \ShortcodeModule\ShortcodeDataInputModeEnum::MANUAL) {
            if (isset($availableParams['entity_type'])) {
                $params['entity_type'] = 'manual';
            }
            if (isset($availableParams['title'])) {
                $params['title'] = 'Titel';
            }
            if (isset($availableParams['external'])) {
                $params['external'] = 'https://example.ch';
            } elseif (isset($availableParams['href'])) {
                $params['href'] = 'https://example.ch';
            }
            if (isset($availableParams['image_src'])) {
                $params['image_src'] = 'https://example.ch/bild.jpg';
            }
        }

        if ($dataInputMode === \ShortcodeModule\ShortcodeDataInputModeEnum::JSON) {
            if (isset($availableParams['json'])) {
                $params['json'] = $this->selectExampleJsonFile($usageDefinition);
            }
        }

        if ($dataInputMode === \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY) {
            $target = (string)($usageDefinition->getTargetEntityTypeKeys()[0] ?? '');
            $entityIdField = $this->getFieldKeyByRole(
                $usageDefinition,
                \ShortcodeModule\ShortcodeFieldRoleEnum::ENTITY_ID
            );
            if (isset($availableParams['entity_type']) && $target !== '') {
                $params['entity_type'] = $target;
            }
            if ($entityIdField !== '') {
                $params[$entityIdField] = '123';
            } elseif ($target !== '' && isset($availableParams[$target])) {
                $params[$target] = '123';
            }
        }

        if ($dataInputMode === \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION) {
            $itemsField = $this->getFieldKeyByRole(
                $usageDefinition,
                \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_ITEMS
            );
            $sourceField = $this->getFieldKeyByRole(
                $usageDefinition,
                \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE
            );
            $sourceIdField = $this->getFieldKeyByRole(
                $usageDefinition,
                \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SOURCE_ID
            );
            $sortField = $this->getFieldKeyByRole(
                $usageDefinition,
                \ShortcodeModule\ShortcodeFieldRoleEnum::COLLECTION_SORT
            );
            $limitField = $this->getFieldKeyByRole(
                $usageDefinition,
                \ShortcodeModule\ShortcodeFieldRoleEnum::LIMIT
            );
            $source = $this->selectExampleSource($usageDefinition);
            if ($sourceField !== '' && $source !== '') {
                $params[$sourceField] = $source;
            }
            if ($sourceIdField !== '' && $this->sourceRequiresSourceId($source)) {
                $params[$sourceIdField] = '123';
            }
            if ($sortField !== '' && $this->sourceSupportsSort($source)) {
                $params[$sortField] = $this->selectExampleSort($source);
            }
            if ($itemsField !== '' && $source === '') {
                $params[$itemsField] = $this->buildItemsExampleValue($usageDefinition);
            }
            if ($limitField !== '') {
                $params[$limitField] = '5';
            }
        }

        return $params;
    }

    private function selectExampleJsonFile(\ShortcodeModule\ShortcodeUsageDefinition $usageDefinition): string
    {
        if (!class_exists(ComponentJsonDataLoader::class)) {
            return 'example.json';
        }

        $fileNames = ComponentJsonDataLoader::getJsonFileNames($usageDefinition->getComponentName());

        return (string)($fileNames[0] ?? 'example.json');
    }

    private function buildBaseCollectionExampleParams(\ShortcodeModule\ShortcodeUsageDefinition $usageDefinition): array
    {
        $availableParams = array_fill_keys($usageDefinition->getParams(), true);
        $params = [];
        $primaryVariantField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::PRIMARY_VARIANT
        );
        $limitField = $this->getFieldKeyByRole(
            $usageDefinition,
            \ShortcodeModule\ShortcodeFieldRoleEnum::LIMIT
        );

        if ($primaryVariantField !== '') {
            $params[$primaryVariantField] = $usageDefinition->getComponentName();
        }
        if (isset($availableParams['title'])) {
            $params['title'] = 'Titel';
        }
        if ($limitField !== '') {
            $params[$limitField] = '5';
        }

        return $params;
    }

    private function selectExampleSource(\ShortcodeModule\ShortcodeUsageDefinition $usageDefinition): string
    {
        $sources = $usageDefinition->getSources();
        if (empty($sources)) {
            return '';
        }

        foreach (['product_category', 'event_category', 'product_manufacturer'] as $preferredSource) {
            if (in_array($preferredSource, $sources, true)) {
                return $preferredSource;
            }
        }

        return (string)$sources[0];
    }

    private function buildItemsExampleValue(\ShortcodeModule\ShortcodeUsageDefinition $usageDefinition): string
    {
        $targetEntityTypeKeys = $usageDefinition->getTargetEntityTypeKeys();
        if ($targetEntityTypeKeys === ['product']) {
            return 'product:82,product:93';
        }

        if (in_array('product', $targetEntityTypeKeys, true) && in_array('community_event', $targetEntityTypeKeys, true)) {
            return 'product:82,community_event:10';
        }

        $target = (string)($targetEntityTypeKeys[0] ?? 'product');

        return $target . ':123';
    }

    private function sourceRequiresSourceId(string $source): bool
    {
        $source = strtolower(trim($source));
        if ($source === '' || !class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)) {
            return false;
        }

        $definition = \CoreExtension\EntityCollectionRelationRegistry::getDefinition($source);

        return $definition instanceof \CoreExtension\EntityCollectionRelationDefinition
            && $definition->requiresSourceId();
    }

    private function sourceSupportsSort(string $source): bool
    {
        $source = strtolower(trim($source));
        if ($source === '' || !class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)) {
            return false;
        }

        $definition = \CoreExtension\EntityCollectionRelationRegistry::getDefinition($source);

        return $definition instanceof \CoreExtension\EntityCollectionRelationDefinition
            && $definition->supportsSort();
    }

    private function selectExampleSort(string $source): string
    {
        $source = strtolower(trim($source));
        if ($source === '' || !class_exists(\CoreExtension\EntityCollectionRelationRegistry::class)) {
            return 'position-asc';
        }

        $definition = \CoreExtension\EntityCollectionRelationRegistry::getDefinition($source);
        if (!$definition instanceof \CoreExtension\EntityCollectionRelationDefinition) {
            return 'position-asc';
        }

        foreach ($definition->getSortOptions() as $sortOption) {
            $value = trim((string)($sortOption['value'] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return 'position-asc';
    }

    private function groupItemsByUsage(array $items): array
    {
        $groups = [];

        foreach ($items as $item) {
            $label = (string)($item['label'] ?? '');
            if ($label === '') {
                continue;
            }

            $targetEntityTypes = array_values(array_filter(array_map('strval', (array)($item['target_entity_types'] ?? []))));
            $groupKey = strtolower($label);

            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [
                    'label' => $label,
                    'target_entity_types' => [],
                    'examples' => [],
                ];
            }

            foreach ($targetEntityTypes as $targetEntityType) {
                if ($targetEntityType !== '') {
                    $groups[$groupKey]['target_entity_types'][$targetEntityType] = $targetEntityType;
                }
            }

            foreach ((array)($item['examples'] ?? []) as $example) {
                if (is_array($example)) {
                    $exampleType = (string)($example['type'] ?? '');
                    $exampleCode = (string)($example['code'] ?? '');
                } else {
                    $exampleType = (string)$example;
                    $exampleCode = (string)$example;
                }

                if ($exampleCode !== '') {
                    $exampleKey = $exampleType !== '' ? $exampleType : $exampleCode;
                    if (!isset($groups[$groupKey]['examples'][$exampleKey])) {
                        $groups[$groupKey]['examples'][$exampleKey] = $exampleCode;
                    }
                }
            }
        }

        foreach ($groups as &$group) {
            ksort($group['target_entity_types']);
            $group['target_entity_types'] = array_values($group['target_entity_types']);
            $group['examples'] = array_values($group['examples']);
        }
        unset($group);

        return array_values($groups);
    }

    private function isContentBlock(string $shortcodeName): bool
    {
        return strtolower((string)($this->getShortcodeContract($shortcodeName)['shortcode_type'] ?? '')) === 'contentblock';
    }

    private function getShortcodeContract(string $shortcodeName): array
    {
        $shortcodeName = strtolower(trim($shortcodeName));
        if ($shortcodeName === '') {
            return [];
        }

        return $this->getShortcodeContractsByName()[$shortcodeName] ?? [];
    }

    private function getFieldKeyByRole(
        \ShortcodeModule\ShortcodeUsageDefinition $usageDefinition,
        \ShortcodeModule\ShortcodeFieldRoleEnum $role
    ): string {
        $availableParams = array_fill_keys($usageDefinition->getParams(), true);
        $contract = $this->getShortcodeContract($usageDefinition->getShortcodeName());
        $fieldKey = trim((string)($contract['field_roles'][$role->value] ?? ''));

        if ($fieldKey !== '' && isset($availableParams[$fieldKey])) {
            return $fieldKey;
        }

        return '';
    }

    private function getShortcodeContractsByName(): array
    {
        if ($this->shortcodeContractsByName !== null) {
            return $this->shortcodeContractsByName;
        }

        $contractsByName = [];
        if (class_exists('Genzo_Shortcodes')) {
            $editorContract = Genzo_Shortcodes::getEditorContract();
            foreach ((array)($editorContract['shortcodes'] ?? []) as $contract) {
                if (is_array($contract) && !empty($contract['name'])) {
                    $contractsByName[strtolower((string)$contract['name'])] = $contract;
                }
            }
        }

        $this->shortcodeContractsByName = $contractsByName;

        return $this->shortcodeContractsByName;
    }
}
