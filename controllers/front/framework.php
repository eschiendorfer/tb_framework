<?php

require_once(dirname(__FILE__).'/../../classes/FrameworkCatalogBuilder.php');
require_once(dirname(__FILE__).'/../../classes/FrameworkIconCatalogBuilder.php');

class tb_frameworkFrameworkModuleFrontController extends ModuleFrontController {
    public $errors;

    public function __construct() {
        parent::__construct();

        $this->display_column_left = false;
        $this->display_column_right = false;
    }

    public function init() {
        parent::init();

        if (!$this->isCurrentCustomerAllowedToViewFramework()) {
            Tools::redirect('index.php?controller=404');
        }
    }

    public function initContent() {
        parent::initContent();

        CssTokenRegistry::assignCssSelectorsToSmarty();

        $catalogBuilder = new FrameworkCatalogBuilder();
        $iconCatalogBuilder = new FrameworkIconCatalogBuilder();
        $catalogItems = $catalogBuilder->all();
        $selectedChannels = $this->resolveSelectedChannels();
        $selectedDataInputModes = $this->resolveSelectedDataInputModes();
        $availableTargetEntityTypeKeys = $catalogBuilder->getAvailableTargetEntityTypeKeys($catalogItems);
        $selectedTargetEntityTypeKeys = $this->resolveSelectedTargetEntityTypeKeys($availableTargetEntityTypeKeys);
        $selectedCatalogKind = $this->resolveSelectedCatalogKind();
        $filteredCatalogItems = $catalogBuilder->filterItems(
            $catalogItems,
            $selectedChannels,
            $selectedDataInputModes,
            $selectedTargetEntityTypeKeys
        );
        $visibleCatalogItems = $this->filterItemsByKind($filteredCatalogItems, $selectedCatalogKind);
        $type = $this->resolveRequestedType($this->getAvailableTypes($catalogBuilder, $catalogItems, $iconCatalogBuilder));
        $component = (bool)(int)Tools::getValue('component');

        $this->context->smarty->assign(array(
            'nobots' => true,
            'nofollow' => true,
            'meta_title' => 'TB FrontOffice Framework',
            'meta_description' => 'Internal Framework for spielezar.ch',
            'site_title' => 'TB Framework',
            'site_description' => 'HTML-Komponenten und CSS-Klassen fuer Frontoffice und Mail.',
            'site_image' => '',
            'framework_content' => $type ? $this->renderFrameworkContentByType(
                $catalogBuilder,
                $iconCatalogBuilder,
                $visibleCatalogItems,
                $type,
                $component,
                $selectedChannels,
                $selectedDataInputModes,
                $selectedTargetEntityTypeKeys
            ) : '',
            'framework_navigation_sections' => $this->buildNavigationSections(
                $filteredCatalogItems,
                $selectedChannels,
                $selectedDataInputModes,
                $selectedTargetEntityTypeKeys,
                $type,
                $selectedCatalogKind,
                $iconCatalogBuilder->count()
            ),
            'framework_channel_filters' => $this->buildChannelFilterOptions($selectedChannels),
            'framework_data_input_filters' => $this->buildDataInputModeFilterOptions($selectedDataInputModes),
            'framework_target_entity_filters' => $this->buildTargetEntityFilterOptions(
                $availableTargetEntityTypeKeys,
                $selectedTargetEntityTypeKeys
            ),
            'framework_current_type' => $type,
            'framework_current_component' => $component,
            'framework_current_kind' => $selectedCatalogKind ? $selectedCatalogKind->value : '',
        ));

        $this->setTemplate('framework.tpl');
    }

    private function renderFrameworkContentByType(
        FrameworkCatalogBuilder $catalogBuilder,
        FrameworkIconCatalogBuilder $iconCatalogBuilder,
        array $catalogItems,
        string $type,
        bool $component,
        array $selectedChannels,
        array $selectedDataInputModes,
        array $selectedTargetEntityTypeKeys
    ) {
        if ($type === $iconCatalogBuilder->getType()) {
            $this->context->smarty->assign(array(
                'title' => 'Icons',
                'icon_groups' => $iconCatalogBuilder->grouped(),
            ));

            return $this->context->smarty->fetch(_PS_MODULE_DIR_.'tb_framework/views/templates/helper/icons.tpl');
        }

        if ($component) {
            $this->context->smarty->assign(array(
                'title' => $this->formatTypeLabel($type),
                'components' => $catalogBuilder->buildPreviewGroups(
                    $catalogItems,
                    $type,
                    $selectedChannels,
                    $selectedDataInputModes,
                    $selectedTargetEntityTypeKeys
                ),
            ));
        }

        $typeTemplate = _PS_MODULE_DIR_.'tb_framework/views/templates/helper/'.$type.'.tpl';
        if (file_exists($typeTemplate)) {
            return $this->context->smarty->fetch($typeTemplate);
        }

        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'tb_framework/views/templates/helper/components.tpl');
    }

    private function getAvailableTypes(
        FrameworkCatalogBuilder $catalogBuilder,
        array $catalogItems,
        FrameworkIconCatalogBuilder $iconCatalogBuilder
    ): array {
        $availableTypes = $catalogBuilder->getAvailableTypes($catalogItems);
        $availableTypes[] = $iconCatalogBuilder->getType();

        return array_values(array_unique($availableTypes));
    }

    private function resolveRequestedType(array $availableTypes): string {
        $type = trim((string)Tools::getValue('type'));
        if ($type === '') {
            return '';
        }

        if (!preg_match('/^[a-z0-9_]+$/', $type)) {
            Tools::redirect('index.php?controller=404');
        }

        if (!in_array($type, $availableTypes, true)) {
            Tools::redirect('index.php?controller=404');
        }

        return $type;
    }

    private function isCurrentCustomerAllowedToViewFramework(): bool {
        if (empty($this->context->customer) || !Validate::isLoadedObject($this->context->customer)) {
            return false;
        }

        if (!class_exists('SpielezarHelper')) {
            return false;
        }

        $idCustomer = (int)$this->context->customer->id;

        return SpielezarHelper::checkIfTeamMember($idCustomer)
            || SpielezarHelper::checkIfCustomerIsEmployeeWithInvoiceRights($idCustomer);
    }

    private function buildNavigationSections(
        array $catalogItems,
        array $selectedChannels,
        array $selectedDataInputModes,
        array $selectedTargetEntityTypeKeys,
        string $currentType,
        ?FrameworkCatalogItemKindEnum $selectedCatalogKind,
        int $iconCount
    ): array {
        $sections = [
            FrameworkCatalogItemKindEnum::COMPONENT->value => [
                'kind' => FrameworkCatalogItemKindEnum::COMPONENT->value,
                'label' => 'Components',
                'items' => [],
            ],
            FrameworkCatalogItemKindEnum::CSS_TOKEN->value => [
                'kind' => FrameworkCatalogItemKindEnum::CSS_TOKEN->value,
                'label' => 'CSS Tokens',
                'items' => [],
            ],
            FrameworkCatalogItemKindEnum::ICON->value => [
                'kind' => FrameworkCatalogItemKindEnum::ICON->value,
                'label' => 'Icons',
                'items' => [],
            ],
        ];

        foreach ($catalogItems as $item) {
            if (!$item instanceof FrameworkCatalogItem) {
                continue;
            }

            $type = $item->getType();
            if ($type === '') {
                continue;
            }

            $sectionKey = $item->getKind()->value;
            if (!isset($sections[$sectionKey]['items'][$type])) {
                $sections[$sectionKey]['items'][$type] = [
                    'type' => $type,
                    'kind' => $sectionKey,
                    'label' => $this->formatTypeLabel($type),
                    'count' => 0,
                    'active' => $currentType === $type
                        && $selectedCatalogKind !== null
                        && $selectedCatalogKind === $item->getKind(),
                    'url' => $this->context->link->getModuleLink(
                        'tb_framework',
                        'framework',
                        $this->buildFrameworkRouteParams(
                            $type,
                            true,
                            $selectedChannels,
                            $selectedDataInputModes,
                            $selectedTargetEntityTypeKeys,
                            $item->getKind()
                        )
                    ),
                ];
            }

            $sections[$sectionKey]['items'][$type]['count']++;
        }

        $sections[FrameworkCatalogItemKindEnum::ICON->value]['items'][] = [
            'type' => 'icons',
            'kind' => FrameworkCatalogItemKindEnum::ICON->value,
            'label' => 'Icons',
            'count' => $iconCount,
            'active' => $currentType === 'icons'
                && (
                    $selectedCatalogKind === null
                    || $selectedCatalogKind === FrameworkCatalogItemKindEnum::ICON
                ),
            'url' => $this->context->link->getModuleLink(
                'tb_framework',
                'framework',
                $this->buildFrameworkRouteParams(
                    'icons',
                    false,
                    $selectedChannels,
                    $selectedDataInputModes,
                    $selectedTargetEntityTypeKeys,
                    FrameworkCatalogItemKindEnum::ICON
                )
            ),
        ];

        foreach ($sections as &$section) {
            uasort($section['items'], function ($left, $right) {
                return strcmp($left['label'], $right['label']);
            });

            $section['items'] = array_values($section['items']);
        }
        unset($section);

        return array_values($sections);
    }

    private function resolveSelectedChannels(): array {
        $requestedChannels = Tools::getValue('channels', null);

        if ($requestedChannels === null || $requestedChannels === '') {
            return [];
        }

        $requestedValues = is_array($requestedChannels)
            ? $requestedChannels
            : explode(',', (string)$requestedChannels);

        $selectedChannels = [];
        foreach ($requestedValues as $requestedValue) {
            $channel = \CoreExtension\OutputChannelEnum::tryFrom(trim((string)$requestedValue));
            if ($channel === null) {
                continue;
            }

            $selectedChannels[$channel->value] = $channel;
        }

        return array_values($selectedChannels);
    }

    private function resolveSelectedDataInputModes(): array {
        return $this->resolveSelectedEnumFilter(
            'data_input_modes',
            $this->getSelectableDataInputModes(),
            \ShortcodeModule\ShortcodeDataInputModeEnum::class
        );
    }

    private function resolveSelectedTargetEntityTypeKeys(array $availableTargetEntityTypeKeys): array {
        $requestedValues = Tools::getValue('target_entities', null);

        if ($requestedValues === null || $requestedValues === '') {
            return [];
        }

        $requestedValueList = is_array($requestedValues)
            ? $requestedValues
            : explode(',', (string)$requestedValues);
        $availableValues = array_flip($availableTargetEntityTypeKeys);
        $selectedValues = [];

        foreach ($requestedValueList as $requestedValue) {
            $targetEntityTypeKey = strtolower(trim((string)$requestedValue));
            if ($targetEntityTypeKey === '' || !isset($availableValues[$targetEntityTypeKey])) {
                continue;
            }

            $selectedValues[$targetEntityTypeKey] = $targetEntityTypeKey;
        }

        $selectedValues = array_values($selectedValues);
        sort($selectedValues);

        $availableValuesSorted = $availableTargetEntityTypeKeys;
        sort($availableValuesSorted);

        if ($selectedValues === $availableValuesSorted) {
            return [];
        }

        return $selectedValues;
    }

    private function resolveSelectedCatalogKind(): ?FrameworkCatalogItemKindEnum {
        $catalogKind = trim((string)Tools::getValue('catalog_kind', ''));

        if ($catalogKind === '') {
            return null;
        }

        return FrameworkCatalogItemKindEnum::tryFrom($catalogKind);
    }

    private function filterItemsByKind(array $items, ?FrameworkCatalogItemKindEnum $catalogKind): array {
        if ($catalogKind === null) {
            return $items;
        }

        return array_values(array_filter(
            $items,
            static function (FrameworkCatalogItem $item) use ($catalogKind): bool {
                return $item->getKind() === $catalogKind;
            }
        ));
    }

    private function resolveSelectedEnumFilter(
        string $valueName,
        array $supportedValues,
        string $enumClass
    ): array {
        $requestedValues = Tools::getValue($valueName, null);

        if ($requestedValues === null || $requestedValues === '') {
            return [];
        }

        $requestedValueList = is_array($requestedValues)
            ? $requestedValues
            : explode(',', (string)$requestedValues);

        $supportedValueMap = [];
        foreach ($supportedValues as $supportedValue) {
            if ($supportedValue instanceof BackedEnum) {
                $supportedValueMap[$supportedValue->value] = true;
            }
        }

        $selectedValues = [];
        foreach ($requestedValueList as $requestedValue) {
            $enumValue = $enumClass::tryFrom(trim((string)$requestedValue));
            if ($enumValue === null) {
                continue;
            }

            if (!isset($supportedValueMap[$enumValue->value])) {
                continue;
            }

            $selectedValues[$enumValue->value] = $enumValue;
        }

        return array_values($selectedValues);
    }

    private function buildChannelFilterOptions(array $selectedChannels): array {
        $selectedChannelValues = array_flip($this->getChannelValues($selectedChannels));
        $options = [];

        foreach ($this->getSupportedChannels() as $channel) {
            $options[] = [
                'value' => $channel->value,
                'label' => $this->getChannelLabel($channel),
                'selected' => isset($selectedChannelValues[$channel->value]),
            ];
        }

        return $options;
    }

    private function buildDataInputModeFilterOptions(array $selectedDataInputModes): array {
        $selectedValues = array_flip($this->getDataInputModeValues($selectedDataInputModes));
        $options = [];

        foreach ($this->getSelectableDataInputModes() as $dataInputMode) {
            $options[] = [
                'value' => $dataInputMode->value,
                'label' => $this->getDataInputModeLabel($dataInputMode),
                'selected' => isset($selectedValues[$dataInputMode->value]),
            ];
        }

        return $options;
    }

    private function buildTargetEntityFilterOptions(
        array $availableTargetEntityTypeKeys,
        array $selectedTargetEntityTypeKeys
    ): array {
        $selectedValues = array_flip($selectedTargetEntityTypeKeys);
        $options = [];

        foreach ($availableTargetEntityTypeKeys as $targetEntityTypeKey) {
            $options[] = [
                'value' => $targetEntityTypeKey,
                'label' => $this->getTargetEntityTypeLabel($targetEntityTypeKey),
                'selected' => isset($selectedValues[$targetEntityTypeKey]),
            ];
        }

        return $options;
    }

    private function buildFrameworkRouteParams(
        string $type,
        bool $component,
        array $selectedChannels,
        array $selectedDataInputModes,
        array $selectedTargetEntityTypeKeys,
        ?FrameworkCatalogItemKindEnum $catalogKind = null
    ): array {
        $routeParams = [
            'type' => $type,
        ];

        $selectedChannelValues = $this->getChannelValues($selectedChannels);
        if (!empty($selectedChannelValues)) {
            $routeParams['channels'] = implode(',', $selectedChannelValues);
        }

        $selectedDataInputModeValues = $this->getSelectableDataInputModeValues($selectedDataInputModes);
        if (!empty($selectedDataInputModeValues)) {
            $routeParams['data_input_modes'] = implode(',', $selectedDataInputModeValues);
        }

        if (!empty($selectedTargetEntityTypeKeys)) {
            $routeParams['target_entities'] = implode(',', $selectedTargetEntityTypeKeys);
        }

        if ($catalogKind !== null) {
            $routeParams['catalog_kind'] = $catalogKind->value;
        }

        if ($component) {
            $routeParams['component'] = 1;
        }

        return $routeParams;
    }

    private function getSupportedChannels(): array {
        return [
            \CoreExtension\OutputChannelEnum::WEB,
            \CoreExtension\OutputChannelEnum::EMAIL,
        ];
    }

    private function getSelectableDataInputModes(): array {
        return [
            \ShortcodeModule\ShortcodeDataInputModeEnum::MANUAL,
            \ShortcodeModule\ShortcodeDataInputModeEnum::JSON,
            \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY,
            \ShortcodeModule\ShortcodeDataInputModeEnum::ENTITY_COLLECTION,
        ];
    }

    private function getChannelValues(array $channels): array {
        return array_map(
            static function (\CoreExtension\OutputChannelEnum $channel): string {
                return $channel->value;
            },
            $channels
        );
    }

    private function getDataInputModeValues(array $dataInputModes): array {
        return array_map(
            static function (\ShortcodeModule\ShortcodeDataInputModeEnum $dataInputMode): string {
                return $dataInputMode->value;
            },
            $dataInputModes
        );
    }

    private function getSelectableDataInputModeValues(array $dataInputModes): array {
        $selectableDataInputModes = $this->getSelectableDataInputModes();

        return $this->getDataInputModeValues(array_values(array_filter(
            $dataInputModes,
            static function (\ShortcodeModule\ShortcodeDataInputModeEnum $dataInputMode) use ($selectableDataInputModes): bool {
                return in_array($dataInputMode, $selectableDataInputModes, true);
            }
        )));
    }

    private function getChannelLabel(\CoreExtension\OutputChannelEnum $channel): string {
        return match ($channel) {
            \CoreExtension\OutputChannelEnum::WEB => 'Web',
            \CoreExtension\OutputChannelEnum::EMAIL => 'Mail',
        };
    }

    private function getDataInputModeLabel(\ShortcodeModule\ShortcodeDataInputModeEnum $dataInputMode): string {
        return $dataInputMode->getLabel();
    }

    private function getTargetEntityTypeLabel(string $targetEntityTypeKey): string {
        return match ($targetEntityTypeKey) {
            'none' => 'Ohne Entity',
            'blog' => 'Blog',
            'category' => 'Kategorie',
            'cms' => 'CMS',
            'customproductlist' => 'Custom Product List',
            'community_event' => 'Event',
            'manufacturer' => 'Hersteller',
            'product' => 'Produkt',
            default => $this->formatTypeLabel($targetEntityTypeKey),
        };
    }

    private function formatTypeLabel(string $type): string {
        return ucwords(str_replace(['_', '-'], ' ', $type));
    }
}
