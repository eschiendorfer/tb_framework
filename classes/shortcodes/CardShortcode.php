<?php

require_once(dirname(__DIR__).'/FrameworkRegistry.php');
require_once(dirname(__FILE__) . '/CardProductDataBridge.php');
require_once(dirname(__FILE__) . '/CardDefaultDataBridge.php');
require_once(dirname(__FILE__) . '/CardTeaserDataBridge.php');

class CardShortcode implements \ShortcodeModule\ShortcodeInterface, \ShortcodeModule\CustomerContentEditShortcodeInterface
{
    private const SHORTCODE_NAME = 'card';
    private const COMPONENT_TYPE = 'card';
    private const COMPONENT_CARD_PRODUCT = 'card_product';
    private const COMPONENT_CARD_DEFAULT = 'card_default';
    private const COMPONENT_CARD_TEASER = 'card_teaser';
    private const ENTITY_TYPE_PRODUCT = CardProductDataBridge::ENTITY_TYPE_PRODUCT;
    private const ENTITY_TYPE_CATEGORY = CardDefaultDataBridge::ENTITY_TYPE_CATEGORY;
    private const ENTITY_TYPE_MANUFACTURER = CardDefaultDataBridge::ENTITY_TYPE_MANUFACTURER;
    private const ENTITY_TYPE_BLOG = CardTeaserDataBridge::ENTITY_TYPE_BLOG;
    private const ENTITY_TYPE_MANUAL = 'manual';

    public static function getName(): string
    {
        return self::SHORTCODE_NAME;
    }

    public static function getAllowedChannels(): array
    {
        return [
            ComponentChannel::WEB->value,
            ComponentChannel::EMAIL->value,
        ];
    }

    public static function getCacheKeyEnum(): ?\CoreExtension\CacheKeysEnum
    {
        return null;
    }

    public static function isAjaxOnly(): bool
    {
        return false;
    }

    public static function getShortcodeType(): \ShortcodeModule\ShortcodeType
    {
        return \ShortcodeModule\ShortcodeType::PARAMETER;
    }

    public static function getAllowedContext(): \ShortcodeModule\ShortcodeContext
    {
        return \ShortcodeModule\ShortcodeContext::ALL;
    }

    public static function getEditorAvailability(): array
    {
        return \ShortcodeModule\EditorContractHelper::availability('Card', self::getAllowedChannels());
    }

    public static function getEditorRender(): array
    {
        return \ShortcodeModule\EditorContractHelper::render(\ShortcodeModule\ShortcodeRenderTypeEnum::FRAMEWORK_COMPONENT);
    }

    public static function getEditorEntity(): array
    {
        $entity = \ShortcodeModule\EditorContractHelper::entityConfig([
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(
                \CoreExtension\EntityTypeEnum::PRODUCT,
                'Produkt',
                'entity_type',
                false,
                ''
            ),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(
                \CoreExtension\EntityTypeEnum::CATEGORY,
                'Kategorie',
                'entity_type',
                false,
                ''
            ),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(
                \CoreExtension\EntityTypeEnum::MANUFACTURER,
                'Hersteller',
                'entity_type',
                false,
                ''
            ),
            \ShortcodeModule\EditorContractHelper::entityTypeFromEnum(
                \CoreExtension\EntityTypeEnum::BLOG,
                'Blog',
                'entity_type',
                false,
                ''
            ),
            \ShortcodeModule\EditorContractHelper::entityTypeCustom(
                self::ENTITY_TYPE_MANUAL,
                'Manuell',
                'entity_type',
                false,
                ''
            ),
        ]);

        $entity['types_by_field'] = [
            'name' => self::getEntityTypeKeysByComponent(),
        ];

        return $entity;
    }

    public static function getEditorFields(): array
    {
        $componentOptionsWeb = self::getComponentOptionsByChannel(ComponentChannel::WEB);
        $componentOptionsEmail = self::getComponentOptionsByChannel(ComponentChannel::EMAIL);
        $styleOptionsByComponent = self::getStyleOptionsByComponent();

        return [
            \ShortcodeModule\EditorContractHelper::field('name', 'Komponente', \CoreExtension\FormFieldTypeEnum::SELECT, true, 'name', [
                'role' => 'component',
                'options' => $componentOptionsWeb,
                'options_by_field' => [
                    'channel' => [
                        ComponentChannel::WEB->value => $componentOptionsWeb,
                        ComponentChannel::EMAIL->value => $componentOptionsEmail,
                    ],
                ],
                'default' => self::COMPONENT_CARD_PRODUCT,
            ]),
            \ShortcodeModule\EditorContractHelper::field('style', 'Style', \CoreExtension\FormFieldTypeEnum::SELECT, false, 'style', [
                'role' => 'style',
                'options' => [['label' => 'default', 'value' => 'default']],
                'options_by_field' => [
                    'name' => $styleOptionsByComponent,
                ],
                'default' => 'default',
            ]),
            \ShortcodeModule\EditorContractHelper::field('id_entity', 'ID / Wert', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'id_entity', [
                'visible_if' => [
                    'field' => 'entity_type',
                    'operator' => 'in',
                    'value' => [
                        self::ENTITY_TYPE_PRODUCT,
                        self::ENTITY_TYPE_CATEGORY,
                        self::ENTITY_TYPE_MANUFACTURER,
                        self::ENTITY_TYPE_BLOG,
                    ],
                ],
                'required_if' => [
                    'field' => 'entity_type',
                    'operator' => 'in',
                    'value' => [
                        self::ENTITY_TYPE_PRODUCT,
                        self::ENTITY_TYPE_CATEGORY,
                        self::ENTITY_TYPE_MANUFACTURER,
                        self::ENTITY_TYPE_BLOG,
                    ],
                ],
                'placeholder' => 'ID / Wert',
            ]),
            \ShortcodeModule\EditorContractHelper::field('description', 'Beschreibung', \CoreExtension\FormFieldTypeEnum::TEXTAREA, false, 'description', [
                'visible_if' => ['field' => 'name', 'operator' => 'equals', 'value' => self::COMPONENT_CARD_TEASER],
            ]),
            \ShortcodeModule\EditorContractHelper::field('title', 'Titel', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'title', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
                'required_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
            ]),
            \ShortcodeModule\EditorContractHelper::field('section_title', 'Sektion Titel', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'section_title', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
            ]),
            \ShortcodeModule\EditorContractHelper::field('section_url', 'Sektion URL', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'section_url', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
                'placeholder' => '/blog/example',
            ]),
            \ShortcodeModule\EditorContractHelper::field('description_manual', 'Beschreibung', \CoreExtension\FormFieldTypeEnum::TEXTAREA, false, 'description_manual', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
            ]),
            \ShortcodeModule\EditorContractHelper::field('image_src', 'Bild URL', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'image_src', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
                'required_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
                'placeholder' => 'https://...',
            ]),
            \ShortcodeModule\EditorContractHelper::field('link_url', 'Link URL', \CoreExtension\FormFieldTypeEnum::TEXT, false, 'link_url', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
                'placeholder' => '/blog/example',
            ]),
            \ShortcodeModule\EditorContractHelper::field('html', 'HTML', \CoreExtension\FormFieldTypeEnum::TEXTAREA, false, 'html', [
                'visible_if' => ['field' => 'entity_type', 'operator' => 'equals', 'value' => self::ENTITY_TYPE_MANUAL],
            ]),
        ];
    }

    public function render(array $params, string $channel, array $context = []): string
    {
        $outputMode = (string)($context['shortcode_output_mode'] ?? \ShortcodeModule\ShortcodeOutputMode::HTML->value);
        $componentChannel = self::resolveComponentChannel($channel);
        $componentName = self::resolveComponentName($params, $componentChannel);
        $component = self::resolveAllowedComponent($componentName, $componentChannel);
        if ($component === null) {
            return '';
        }

        $entityType = trim(strtolower((string)($params['entity_type'] ?? '')));
        if (!self::isEntityTypeAllowedForComponent($componentName, $entityType)) {
            return '';
        }

        $style = trim((string)($params['style'] ?? ''));
        $idEntityRaw = trim((string)($params['id_entity'] ?? ''));
        $data = self::resolveDataByComponent($componentName, $entityType, $idEntityRaw, $params, $componentChannel);

        if (empty($data)) {
            return '';
        }

        if ($outputMode === \ShortcodeModule\ShortcodeOutputMode::CUSTOMER_CONTENT_EDIT->value) {
            return self::resolveCustomerContentEditUrl($params, $componentName, $entityType, $data);
        }

        if ($outputMode === \ShortcodeModule\ShortcodeOutputMode::TEXT->value) {
            return self::buildTextOutput($componentName, $data);
        }

        if ($componentChannel === ComponentChannel::EMAIL) {
            return $component::fetchEmail($data, $style);
        }

        return $component::fetchWeb($data, $style);
    }

    public function renderCustomerContentEdit(array $params, string $channel, array $context = []): string
    {
        $componentChannel = self::resolveComponentChannel($channel);
        $componentName = self::resolveComponentName($params, $componentChannel);
        if (self::resolveAllowedComponent($componentName, $componentChannel) === null) {
            return '';
        }

        $entityType = trim(strtolower((string)($params['entity_type'] ?? '')));
        if (!self::isEntityTypeAllowedForComponent($componentName, $entityType)) {
            return '';
        }

        $idEntityRaw = trim((string)($params['id_entity'] ?? ''));
        $data = self::resolveDataByComponent($componentName, $entityType, $idEntityRaw, $params, $componentChannel);
        if (empty($data)) {
            return '';
        }

        return self::resolveCustomerContentEditUrl($params, $componentName, $entityType, $data);
    }

    private static function resolveCustomerContentEditUrl(
        array $params,
        string $componentName,
        string $entityType,
        array $data
    ): string {
        if (!self::isAutomaticProductTeaser($params, $componentName, $entityType)) {
            return '';
        }

        $url = trim((string)($data['link']['url'] ?? ''));
        if (!self::isCustomerContentUrl($url)) {
            return '';
        }

        return $url;
    }

    private static function isAutomaticProductTeaser(array $params, string $componentName, string $entityType): bool
    {
        if ($componentName !== self::COMPONENT_CARD_TEASER || $entityType !== self::ENTITY_TYPE_PRODUCT) {
            return false;
        }

        $expectedKeys = ['entity_type' => true, 'id_entity' => true, 'name' => true];
        foreach ($params as $key => $value) {
            if (!isset($expectedKeys[(string)$key])) {
                return false;
            }
        }

        return isset($params['id_entity']) && (int)$params['id_entity'] > 0;
    }

    private static function isCustomerContentUrl(string $value): bool
    {
        return (bool)preg_match('#^https://[^\s<>"\']+$#i', $value);
    }

    private static function resolveDataByComponent(
        string $componentName,
        string $entityType,
        string $idEntityRaw,
        array $params,
        ComponentChannel $componentChannel
    ): array {
        if ($componentName === self::COMPONENT_CARD_PRODUCT) {
            if ($idEntityRaw === '') {
                return [];
            }

            return CardProductDataBridge::resolveEntityData(
                $entityType,
                (int)$idEntityRaw,
                $componentChannel
            );
        }

        if ($componentName === self::COMPONENT_CARD_DEFAULT) {
            if ($entityType === self::ENTITY_TYPE_MANUAL) {
                return self::buildManualData($params);
            }

            if ($idEntityRaw === '') {
                return [];
            }

            return CardDefaultDataBridge::resolveEntityData(
                $entityType,
                (int)$idEntityRaw,
                $componentChannel
            );
        }

        if ($componentName === self::COMPONENT_CARD_TEASER) {
            if ($idEntityRaw === '') {
                return [];
            }

            return CardTeaserDataBridge::resolveEntityData(
                $entityType,
                (int)$idEntityRaw,
                $params,
                $componentChannel
            );
        }

        return [];
    }

    private static function buildManualData(array $params): array
    {
        $title = trim((string)($params['title'] ?? ''));
        $imageSrc = trim((string)($params['image_src'] ?? ''));

        if ($title === '' || $imageSrc === '') {
            return [];
        }

        $data = [
            'title' => $title,
            'image' => [
                'src' => $imageSrc,
            ],
        ];

        $sectionTitle = trim((string)($params['section_title'] ?? ''));
        $sectionUrl = trim((string)($params['section_url'] ?? ''));
        if ($sectionTitle !== '' && $sectionUrl !== '') {
            $data['section'] = [
                'title' => $sectionTitle,
                'url' => $sectionUrl,
            ];
        }

        $description = trim((string)($params['description_manual'] ?? ''));
        if ($description === '') {
            $description = trim((string)($params['description'] ?? ''));
        }
        if ($description !== '') {
            $data['description'] = $description;
        }

        $linkUrl = trim((string)($params['link_url'] ?? ''));
        if ($linkUrl !== '') {
            $data['link'] = [
                'url' => $linkUrl,
            ];
        }

        $html = (string)($params['html'] ?? '');
        if ($html !== '') {
            $data['html'] = $html;
        }

        return $data;
    }

    private static function buildTextOutput(string $componentName, array $data): string
    {
        if ($componentName === self::COMPONENT_CARD_PRODUCT) {
            return trim((string)($data['name'] ?? ''));
        }

        if ($componentName === self::COMPONENT_CARD_DEFAULT) {
            return trim((string)($data['title'] ?? ''));
        }

        if ($componentName === self::COMPONENT_CARD_TEASER) {
            $title = trim((string)($data['title'] ?? ''));
            $url = trim((string)($data['link']['url'] ?? ''));
            if ($url !== '') {
                return $title . ' (' . $url . ')';
            }

            return $title;
        }

        return '';
    }

    private static function resolveComponentName(array $params, ComponentChannel $channel): string
    {
        $componentName = trim((string)($params['name'] ?? ''));
        if ($componentName !== '') {
            return $componentName;
        }

        return self::resolveDefaultComponentByChannel($channel);
    }

    private static function resolveAllowedComponent(string $componentName, ComponentChannel $channel): ?ComponentDefinition
    {
        $component = FrameworkRegistry::getByName($componentName);

        if (!$component) {
            return null;
        }

        if ($component->getType() !== self::COMPONENT_TYPE) {
            return null;
        }

        if (!in_array($componentName, self::getSupportedComponentNames(), true)) {
            return null;
        }

        if (!in_array($channel, $component->getChannels(), true)) {
            return null;
        }

        return $component;
    }

    /**
     * @return array<string, array<int, string>>
     */
    private static function getEntityTypeKeysByComponent(): array
    {
        return [
            self::COMPONENT_CARD_PRODUCT => [self::ENTITY_TYPE_PRODUCT],
            self::COMPONENT_CARD_DEFAULT => [
                self::ENTITY_TYPE_MANUAL,
                self::ENTITY_TYPE_PRODUCT,
                self::ENTITY_TYPE_CATEGORY,
                self::ENTITY_TYPE_MANUFACTURER,
            ],
            self::COMPONENT_CARD_TEASER => [
                self::ENTITY_TYPE_PRODUCT,
                self::ENTITY_TYPE_BLOG,
                self::ENTITY_TYPE_MANUFACTURER,
            ],
        ];
    }

    private static function isEntityTypeAllowedForComponent(string $componentName, string $entityType): bool
    {
        $entityType = trim(strtolower($entityType));
        if ($entityType === '') {
            return false;
        }

        $allowed = self::getEntityTypeKeysByComponent()[$componentName] ?? [];
        return in_array($entityType, $allowed, true);
    }

    /**
     * @return array<int, array{value:string,label:string}>
     */
    private static function getComponentOptionsByChannel(ComponentChannel $channel): array
    {
        $options = [];

        foreach (self::getSupportedComponentNames() as $componentName) {
            $component = FrameworkRegistry::getByName($componentName);
            if (!$component) {
                continue;
            }

            if (!in_array($channel, $component->getChannels(), true)) {
                continue;
            }

            $options[] = [
                'value' => $componentName,
                'label' => self::formatLabel($componentName),
            ];
        }

        return $options;
    }

    /**
     * @return array<string, array<int, array{value:string,label:string}>>
     */
    private static function getStyleOptionsByComponent(): array
    {
        $stylesByComponent = [];

        foreach (self::getSupportedComponentNames() as $componentName) {
            $component = FrameworkRegistry::getByName($componentName);
            if (!$component) {
                continue;
            }

            $styles = [];
            foreach ($component->getStyles() as $style) {
                $styles[] = [
                    'value' => (string)$style,
                    'label' => (string)$style,
                ];
            }

            if (!empty($styles)) {
                $stylesByComponent[$componentName] = $styles;
            }
        }

        return $stylesByComponent;
    }

    private static function resolveDefaultComponentByChannel(ComponentChannel $channel): string
    {
        $options = self::getComponentOptionsByChannel($channel);
        if (!empty($options[0]['value'])) {
            return (string)$options[0]['value'];
        }

        return self::COMPONENT_CARD_PRODUCT;
    }

    /**
     * @return string[]
     */
    private static function getSupportedComponentNames(): array
    {
        return [
            self::COMPONENT_CARD_PRODUCT,
            self::COMPONENT_CARD_DEFAULT,
            self::COMPONENT_CARD_TEASER,
        ];
    }

    private static function resolveComponentChannel(string $runtimeChannel): ComponentChannel
    {
        $runtimeChannel = trim(strtolower($runtimeChannel));

        if ($runtimeChannel === ComponentChannel::EMAIL->value) {
            return ComponentChannel::EMAIL;
        }

        return ComponentChannel::WEB;
    }

    private static function formatLabel(string $value): string
    {
        return ucwords(str_replace('_', ' ', trim($value)));
    }
}
