<?php

require_once(dirname(__FILE__).'/FrameworkRegistry.php');
require_once(dirname(__FILE__).'/CssTokenRegistry.php');
require_once(dirname(__FILE__).'/FrameworkCatalogItem.php');
require_once(dirname(__FILE__).'/FrameworkCatalogUsageViewBuilder.php');
require_once(dirname(__FILE__).'/shortcodes/ComponentJsonDataLoader.php');

if (defined('_PS_MODULE_DIR_') && file_exists(_PS_MODULE_DIR_.'genzo_shortcodes/autoload.php')) {
    include_once _PS_MODULE_DIR_.'genzo_shortcodes/autoload.php';
}

class FrameworkCatalogBuilder {
    private ?array $usageDefinitionsByComponentName = null;
    private ?FrameworkCatalogUsageViewBuilder $usageViewBuilder = null;

    public function all(): array {
        $items = [];

        foreach (FrameworkRegistry::all() as $component) {
            $items[] = $this->createComponentItem($component);
        }

        foreach (CssTokenRegistry::all() as $cssToken) {
            $items[] = $this->createCssTokenItem($cssToken);
        }

        usort($items, static function (FrameworkCatalogItem $left, FrameworkCatalogItem $right): int {
            $typeComparison = strcmp($left->getType(), $right->getType());
            if ($typeComparison !== 0) {
                return $typeComparison;
            }

            return strcmp($left->getName(), $right->getName());
        });

        return $items;
    }

    public function filterItems(
        array $items,
        array $selectedChannels,
        array $selectedDataInputModes,
        array $selectedTargetEntityTypeKeys = []
    ): array {
        $filteredItems = [];

        foreach ($items as $item) {
            if (!$item instanceof FrameworkCatalogItem) {
                continue;
            }

            if (!$item->isAvailableForChannels($selectedChannels)) {
                continue;
            }

            if (!$item->matchesDataInputModes($selectedDataInputModes)) {
                continue;
            }

            if (!$item->matchesTargetEntityTypeKeys($selectedTargetEntityTypeKeys)) {
                continue;
            }

            $filteredItems[] = $item;
        }

        return $filteredItems;
    }

    public function getAvailableTypes(array $items): array {
        $types = [];

        foreach ($items as $item) {
            if (!$item instanceof FrameworkCatalogItem) {
                continue;
            }

            $type = $item->getType();
            if ($type === '' || !preg_match('/^[a-z0-9_]+$/', $type)) {
                continue;
            }

            $types[$type] = true;
        }

        return array_keys($types);
    }

    public function getAvailableTargetEntityTypeKeys(array $items): array {
        $targetEntityTypeKeys = [];

        foreach ($items as $item) {
            if (!$item instanceof FrameworkCatalogItem) {
                continue;
            }

            foreach ($item->getTargetEntityTypeKeys() as $targetEntityTypeKey) {
                $targetEntityTypeKeys[$targetEntityTypeKey] = $targetEntityTypeKey;
            }

            if (empty($item->getTargetEntityTypeKeys())) {
                $targetEntityTypeKeys['none'] = 'none';
            }
        }

        ksort($targetEntityTypeKeys);

        return array_values($targetEntityTypeKeys);
    }

    public function buildPreviewGroups(
        array $items,
        string $type,
        array $selectedChannels,
        array $selectedDataInputModes,
        array $selectedTargetEntityTypeKeys = []
    ): array {
        $previewGroups = [];

        foreach ($items as $item) {
            if (!$item instanceof FrameworkCatalogItem || $item->getType() !== $type) {
                continue;
            }

            $previewGroup = null;
            if ($item->getKind() === FrameworkCatalogItemKindEnum::COMPONENT) {
                $previewGroup = $this->buildComponentPreviewGroup(
                    $item,
                    $selectedChannels,
                    $selectedDataInputModes,
                    $selectedTargetEntityTypeKeys
                );
            } elseif ($item->getKind() === FrameworkCatalogItemKindEnum::CSS_TOKEN) {
                $previewGroup = $this->buildCssTokenPreviewGroup($item, $selectedChannels);
            }

            if ($previewGroup !== null) {
                $previewGroups[] = $previewGroup;
            }
        }

        return $previewGroups;
    }

    private function createComponentItem(ComponentDefinition $component): FrameworkCatalogItem {
        $usageDefinitions = $this->getUsageDefinitionsForComponentName($component->getName());

        return new FrameworkCatalogItem(
            FrameworkCatalogItemKindEnum::COMPONENT,
            $component->getType(),
            $component->getName(),
            $component->getChannels(),
            $this->getDataInputModesFromUsageDefinitions($usageDefinitions),
            $this->getTargetEntityTypeKeysFromUsageDefinitions($usageDefinitions),
            $component,
            null
        );
    }

    private function createCssTokenItem(CssTokenDefinition $cssToken): FrameworkCatalogItem {
        return new FrameworkCatalogItem(
            FrameworkCatalogItemKindEnum::CSS_TOKEN,
            $cssToken->getType(),
            $cssToken->getName(),
            [],
            [\ShortcodeModule\ShortcodeDataInputModeEnum::NONE],
            [],
            null,
            $cssToken
        );
    }

    private function buildComponentPreviewGroup(
        FrameworkCatalogItem $item,
        array $selectedChannels,
        array $selectedDataInputModes,
        array $selectedTargetEntityTypeKeys
    ): ?array {
        $component = $item->getComponent();
        if (!$component) {
            return null;
        }

        $visibleChannels = $component->getChannels();
        if (!empty($selectedChannels)) {
            $visibleChannels = [];
            foreach ($component->getChannels() as $channel) {
                if (in_array($channel, $selectedChannels, true)) {
                    $visibleChannels[] = $channel;
                }
            }
        }

        if (empty($visibleChannels)) {
            return null;
        }

        $usageDefinitions = $this->getUsageViewBuilder()->build(
            $this->getUsageDefinitionsForComponentName($item->getName()),
            $selectedTargetEntityTypeKeys,
            $selectedDataInputModes
        );
        $previewGroup = $this->createBasePreviewGroup(
            $item,
            $usageDefinitions,
            $visibleChannels
        );

        foreach ($visibleChannels as $channel) {
            $outputLabel = $this->getOutputLabelForChannel($channel);
            $outputBadge = $this->getOutputBadge($outputLabel);
            foreach ($component->getStyles() as $style) {
                $previewGroup['variants'][] = [
                    'output_badge' => $outputBadge,
                    'style' => $style,
                    'output' => $component::fetchDemo($channel, $style),
                ];
            }
        }

        return $previewGroup;
    }

    private function buildCssTokenPreviewGroup(
        FrameworkCatalogItem $item,
        array $selectedChannels
    ): ?array {
        if (
            !empty($selectedChannels)
            && !in_array(\CoreExtension\OutputChannelEnum::WEB, $selectedChannels, true)
        ) {
            return null;
        }

        $cssToken = $item->getCssToken();
        if (!$cssToken) {
            return null;
        }

        $outputBadge = $this->getOutputBadge('CSS');
        $previewGroup = $this->createBasePreviewGroup($item, []);

        foreach ($cssToken->getStyles() as $style) {
            $cssClasses = $cssToken->getCssClasses($style);
            $escapedClasses = htmlspecialchars($cssClasses, ENT_QUOTES, 'UTF-8');
            $escapedName = htmlspecialchars($cssToken->getName(), ENT_QUOTES, 'UTF-8');

            $previewGroup['variants'][] = [
                'output_badge' => $outputBadge,
                'style' => $style,
                'output' => '<span class="'.$escapedClasses.'">'.$escapedName.'</span>',
            ];
        }

        return $previewGroup;
    }

    private function createBasePreviewGroup(
        FrameworkCatalogItem $item,
        array $usageDefinitions,
        array $visibleChannels = []
    ): array {
        return [
            'name' => $item->getName(),
            'styles' => $this->getStylesForPreviewGroup($item),
            'json_demo_structure' => $this->getJsonDemoStructureForPreviewGroup($item),
            'usage_examples' => $this->getUsageExamplesForPreviewGroup(
                $item,
                $usageDefinitions,
                $visibleChannels
            ),
            'variants' => [],
        ];
    }

    private function getStylesForPreviewGroup(FrameworkCatalogItem $item): array {
        $component = $item->getComponent();
        if ($component instanceof ComponentDefinition) {
            return $component->getStyles();
        }

        $cssToken = $item->getCssToken();
        if ($cssToken instanceof CssTokenDefinition) {
            return $cssToken->getStyles();
        }

        return [];
    }

    private function getOutputLabelForChannel(\CoreExtension\OutputChannelEnum $channel): string {
        return $channel === \CoreExtension\OutputChannelEnum::EMAIL ? 'MAIL' : 'WEB';
    }

    private function getOutputBadge(string $outputLabel): array {
        $cssTokenName = match ($outputLabel) {
            'WEB' => 'badge_success',
            'MAIL' => 'badge_danger',
            'CSS' => 'badge_warning',
            default => 'badge_default',
        };

        return [
            'label' => $outputLabel,
            'icon' => match ($outputLabel) {
                'WEB' => 'icon-globe',
                'MAIL' => 'icon-envelope',
                'CSS' => 'icon-swatch',
                default => '',
            },
            'class' => $this->getBadgeCssClasses($cssTokenName),
        ];
    }

    private function getBadgeCssClasses(string $cssTokenName): string {
        $cssToken = CssTokenRegistry::getByName($cssTokenName);

        if (!$cssToken instanceof CssTokenDefinition) {
            return '';
        }

        return $cssToken->getCssClasses('small');
    }

    private function getUsageExamplesForPreviewGroup(
        FrameworkCatalogItem $item,
        array $shortcodeUsageExamples,
        array $visibleChannels
    ): array {
        if ($item->getKind() === FrameworkCatalogItemKindEnum::CSS_TOKEN) {
            $cssToken = $item->getCssToken();
            if (!$cssToken) {
                return [];
            }

            return [
                [
                    'label' => '{$css_selector}',
                    'target_entity_types' => [],
                    'examples' => $this->getCssSelectorExamples($cssToken),
                ],
                [
                    'label' => 'CSS-Klasse',
                    'target_entity_types' => [],
                    'examples' => $this->getCssClassExamples($cssToken),
                ],
            ];
        }

        $usageExamples = $shortcodeUsageExamples;

        $fetchExamples = $this->getComponentFetchExamples($item, $visibleChannels);
        if (!empty($fetchExamples)) {
            $usageExamples[] = [
                'label' => 'PHP/TPL',
                'target_entity_types' => [],
                'examples' => $fetchExamples,
            ];
        }

        return $usageExamples;
    }

    private function getCssSelectorExamples(CssTokenDefinition $cssToken): array {
        $examples = [];

        foreach ($cssToken->getStyles() as $style) {
            $examples[] = '{$css_selector.'.$cssToken->getName().'.'.$style.'}';
        }

        return $examples;
    }

    private function getCssClassExamples(CssTokenDefinition $cssToken): array {
        $examples = [];

        foreach ($cssToken->getStyles() as $style) {
            $cssClasses = $cssToken->getCssClasses($style);
            if ($cssClasses === '') {
                continue;
            }

            $examples[$cssClasses] = $cssClasses;
        }

        return array_values($examples);
    }

    private function getComponentFetchExamples(FrameworkCatalogItem $item, array $visibleChannels): array {
        $component = $item->getComponent();
        if (!$component) {
            return [];
        }

        $className = get_class($component);
        $style = $component->getDefaultStyle();
        $examples = [];

        foreach ($visibleChannels as $channel) {
            if (!$channel instanceof \CoreExtension\OutputChannelEnum) {
                continue;
            }

            $method = $channel === \CoreExtension\OutputChannelEnum::EMAIL ? 'fetchEmail' : 'fetchWeb';
            $examples[$method] = $className.'::'.$method.'($data, \''.$style.'\')';
        }

        return array_values($examples);
    }

    private function supportsJsonData(FrameworkCatalogItem $item): bool {
        if ($item->getKind() !== FrameworkCatalogItemKindEnum::COMPONENT) {
            return false;
        }

        return in_array(\ShortcodeModule\ShortcodeDataInputModeEnum::JSON, $item->getDataInputModes(), true);
    }

    private function getJsonDemoStructureForPreviewGroup(FrameworkCatalogItem $item): string {
        if (!$this->supportsJsonData($item)) {
            return '';
        }

        $component = $item->getComponent();

        return $component ? ComponentJsonDataLoader::getDemoJson($component) : '';
    }

    private function getUsageDefinitionsForComponentName(string $componentName): array {
        return $this->getUsageDefinitionsByComponentName()[$componentName] ?? [];
    }

    private function getUsageDefinitionsByComponentName(): array {
        if ($this->usageDefinitionsByComponentName !== null) {
            return $this->usageDefinitionsByComponentName;
        }

        $usageDefinitionsByComponentName = [];

        if (class_exists('Genzo_Shortcodes')) {
            foreach (Genzo_Shortcodes::getUsageDefinitions() as $usageDefinition) {
                if (!$usageDefinition instanceof \ShortcodeModule\ShortcodeUsageDefinition) {
                    continue;
                }

                $componentName = $usageDefinition->getComponentName();
                if ($componentName === '') {
                    continue;
                }

                $usageDefinitionsByComponentName[$componentName][] = $usageDefinition;
            }
        }

        $this->usageDefinitionsByComponentName = $usageDefinitionsByComponentName;

        return $this->usageDefinitionsByComponentName;
    }

    private function getTargetEntityTypeKeysFromUsageDefinitions(array $usageDefinitions): array {
        $targetEntityTypeKeys = [];

        foreach ($usageDefinitions as $usageDefinition) {
            if (!$usageDefinition instanceof \ShortcodeModule\ShortcodeUsageDefinition) {
                continue;
            }

            foreach ($usageDefinition->getTargetEntityTypeKeys() as $targetEntityTypeKey) {
                $targetEntityTypeKeys[$targetEntityTypeKey] = $targetEntityTypeKey;
            }
        }

        ksort($targetEntityTypeKeys);

        return array_values($targetEntityTypeKeys);
    }

    private function getDataInputModesFromUsageDefinitions(array $usageDefinitions): array {
        $dataInputModes = [];

        foreach ($usageDefinitions as $usageDefinition) {
            if (!$usageDefinition instanceof \ShortcodeModule\ShortcodeUsageDefinition) {
                continue;
            }

            $dataInputMode = $usageDefinition->getDataInputMode();
            $dataInputModes[$dataInputMode->value] = $dataInputMode;
        }

        if (empty($dataInputModes)) {
            return [\ShortcodeModule\ShortcodeDataInputModeEnum::MANUAL];
        }

        return array_values($dataInputModes);
    }

    private function getUsageViewBuilder(): FrameworkCatalogUsageViewBuilder {
        if ($this->usageViewBuilder === null) {
            $this->usageViewBuilder = new FrameworkCatalogUsageViewBuilder();
        }

        return $this->usageViewBuilder;
    }
}
